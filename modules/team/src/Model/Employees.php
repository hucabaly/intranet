<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;
use DB;
use Exception;
use Lang;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Rikkei\Recruitment\Model\RecruitmentApplies;
use Rikkei\Core\View\CacheHelper;

class Employees extends CoreModel
{
    
    use SoftDeletes;
    
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 0;
    const CODE_PREFIX = 'RK';
    const CODE_LENGTH = 5;

    const KEY_CACHE = 'employee';
    const KEY_CACHE_PERMISSION_TEAM_ROUTE = 'team_rule_route';
    const KEY_CACHE_PERMISSION_TEAM_ACTION = 'team_rule_action';
    const KEY_CACHE_PERMISSION_ROLE_ROUTE = 'role_rule_route';
    const KEY_CACHE_PERMISSION_ROLE_ACTION = 'role_rule_action';
    
    protected $table = 'employees';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'birthday',
        'nickname',
        'email',
        'employee_card_id',
        'join_date',
        'leave_date',
        'persional_email',
        'mobile_phone',
        'home_phone',
        'gender',
        'address',
         'home_town',
        'id_card_number',
        'id_card_place',
        'id_cart_date',
        'recruitment_apply_id',
        'employee_code',
        'personal_email',
        'state'
    ];
    
    /**
     * get collection to show grid
     * 
     * @return type
     */
    public static function getGridData()
    {
        $pager = Config::getPagerData();
        $collection = self::select('id','name','email', 'employee_code')
            ->orderBy($pager['order'], $pager['dir']);
        $collection = self::filterGrid($collection);
        $collection = $collection->paginate($pager['limit']);
        return $collection;
    }
    
    /**
     * rewrite save model employee
     * 
     * @param array $options
     */
    public function save(array $options = array()) {
        if (! $this->nickname) {
            $this->nickname = preg_replace('/@.*$/', '', $this->email);
        }
        try {
            $this->saveCode();
            $this->saveRecruitmentAppyId();
            CacheHelper::forget(self::KEY_CACHE, $this->id);
            return parent::save($options);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * save recruitment apply id follow phone
     * 
     * @return \Rikkei\Team\Model\Employees
     */
    public function saveRecruitmentAppyId()
    {
        if ($this->recruitment_apply_id || ! $this->mobile_phone) {
            return;
        }
        $recruitment = RecruitmentApplies::select('id')->where('phone', $this->mobile_phone)->first();
        if ($recruitment) {
            $this->recruitment_apply_id = $recruitment->id;
        }
        return $this;
    }
    
    /**
     * save team positon for member
     * 
     * @param array $teamPostions
     * @throws Exception
     */
    public function saveTeamPosition(array $teamPostions = [])
    {
        //check miss data
        foreach ($teamPostions as $teamPostion) {
            if (! isset($teamPostion['team']) || 
                ! isset($teamPostion['position']) ||
                ! $teamPostion['team'] ||
                ! $teamPostion['position']) {
                throw new Exception(Lang::get('team::view.Miss data team or position'));
            }
        }
        //check data team not same
        $lengthTeamPostionsSubmit = count($teamPostions);
        for ($i = 1 ; $i < $lengthTeamPostionsSubmit ;  $i++) {
            for ($j = $i + 1 ; $j <= $lengthTeamPostionsSubmit ; $j ++) {
                if ($teamPostions[$i]['team'] == $teamPostions[$j]['team']) {
                    throw new Exception(Lang::get('team::view.Team same data'));
                }
            }
        }
        DB::beginTransaction();
        try {
            //delete all team position of employee before insert new
            TeamMembers::where('employee_id', $this->id)->delete();
            // set null leader id before set leader new
            Team::where('leader_id', $this->id)->update([
                'leader_id' => null
            ]);
            if (count($teamPostions)) {
                foreach ($teamPostions as $teamPostion) {
                    $team = Team::find($teamPostion['team']);
                    if (! $team) {
                        continue;
                    }
                    $positionLeader = Roles::isPositionLeader($teamPostion['position']);
                    if ($positionLeader === null) { //not found position
                        continue;
                    } else if ($positionLeader === true) { //position is leader
                        $teamLeader = $team->getLeader();
                        if (Team::MAX_LEADER == 1 && $teamLeader && $teamLeader->id != $this->id) { //flag team only have 1 leader
                            throw new Exception(Lang::get('team::messages.Team :name had leader!', ['name' => $team->name]));
                        } elseif (! $teamLeader) { //save leader for team
                            $team->leader_id = $this->id;
                            $team->save();
                        }
                    }
                    $teamMember = new TeamMembers();
                    $teamMember->setData([
                        'team_id' => $teamPostion['team'],
                        'role_id' => $teamPostion['position'],
                        'employee_id' => $this->id
                    ]);
                    $teamMember->save();
                }
            }
            CacheHelper::forget(self::KEY_CACHE, $this->id);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * save role for employee
     * 
     * @param array $roles
     * @throws Exception
     */
    public function saveRoles(array $roles = [])
    {
        DB::beginTransaction();
        try {
            EmployeeRole::where('employee_id', $this->id)->delete();
            if (count($roles)) {
                foreach ($roles as $role) {
                    $employeeRole = new EmployeeRole();
                    $employeeRole->setData([
                        'role_id' => $role,
                        'employee_id' => $this->id
                    ]);
                    $employeeRole->save();
                }
            }
            CacheHelper::forget(self::KEY_CACHE, $this->id);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * set code for employee
     * 
     * @return \Rikkei\Team\Model\Employees
     */
    public function saveCode()
    {
        if ($this->employee_code || ! $this->join_date) {
            return;
        }
        $year = strtotime($this->join_date);
        $year = date('y', $year);
        $codeLast = self::select('employee_code')
            ->where('employee_code', 'like', self::CODE_PREFIX . $year . '%')
            ->orderBy('employee_code', 'DESC')
            ->first();
        if (! $codeLast) {
            $codeEmployee = self::CODE_PREFIX . $year;
            for ($i = 0; $i < self::CODE_LENGTH - 1; $i++) {
                $codeEmployee .= '0';
            }
            $codeEmployee .= '1';
        } else {
            $codeLast = $codeLast->employee_code;
            $codeEmployee = preg_replace('/^' . self::CODE_PREFIX . $year . '/', '', $codeLast);
            $codeEmployee = (int) $codeEmployee + 1;
            $codeEmployee = (string) $codeEmployee;
            $lengthCodeCurrent = strlen($codeEmployee);
            for ($i = 0 ; $i < self::CODE_LENGTH - $lengthCodeCurrent; $i++) {
                $codeEmployee = '0' . $codeEmployee;
            }
            $codeEmployee = self::CODE_PREFIX . $year . $codeEmployee;
        }
        $this->employee_code = $codeEmployee;
        return $this;
    }
    
    /**
     * get team and position of employee
     * 
     * @return collection
     */
    public function getTeamPositons()
    {
        if ($employeeTeam = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeTeam;
        }
        $employeeTeam = TeamMembers::select('team_id', 'role_id')->where('employee_id', $this->id)->get();
        CacheHelper::put(self::KEY_CACHE, $employeeTeam, $this->id);
        return $employeeTeam;
    }
    
    /**
     * get roles of employee
     * 
     * @return collection
     */
    public function getRoles()
    {
        if ($employeeRole = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeRole;
        }
        $employeeRole = EmployeeRole::select('role_id', 'role')
                ->join('roles', 'roles.id', '=', 'employee_roles.role_id')
                ->where('employee_id', $this->id)
                ->orderBy('role')
                ->get();
        CacheHelper::put(self::KEY_CACHE, $employeeRole, $this->id);
        return $employeeRole;
    }
    
    /**
     * get roles of employee
     * 
     * @return collection
     */
    public function getRoleIds()
    {
        if ($employeeRole = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeRole;
        }
        $employeeRole = EmployeeRole::select('role_id')
            ->where('employee_id', $this->id)
            ->get();
        CacheHelper::put(self::KEY_CACHE, $employeeRole, $this->id);
        return $employeeRole;
    }
    
    /**
     * convert collection model to array with key is name column
     * 
     * @param model $collection
     * @param string $collection
     * @return array
     */
    protected static function formatArray($collection, $key = null)
    {
        if (!$collection instanceof \Illuminate\Contracts\Support\Arrayable) {
            return [];
        }
        $collectionArray = $collection->toArray();
        if (! $key) {
            return $collectionArray;
        }
        $result = [];
        foreach ($collectionArray as $item) {
            $result[$item[$key]] = $item;
        }
        return $result;
    }
    
    /**
     * get permission of employee
     * result = array route name and action id allowed follow each team
     * 
     * @return array
     */
    public function getPermission()
    {
        $permissionTeam = $this->getPermissionTeam();
        $permissionRole = $this->getPermissionRole();
        $result = [];
        if ($permissionTeam) {
            $result['team'] = $permissionTeam;
        }
        if ($permissionRole) {
            $result['role'] = $permissionRole;
        }
        return $result;
    }
    
    /**
     * get permission team of employee
     * 
     * @return array
     */
    public function getPermissionTeam()
    {        
        $teams = $this->getTeamPositons();
        if (! $teams || ! count($teams)) {
            return [];
        }
        $routesAllow = [];
        $actionIdsAllow = [];
        $actionTable = Action::getTableName();
        $permissionTable = Permissions::getTableName();
        foreach ($teams as $teamMember) {
            $team = Team::find($teamMember->team_id);
            $teamAs = $team->getTeamPermissionAs();
            $teamIdOrgin = $team->id;
            if ($teamAs) {
                $team = $teamAs;
            }
            $teamIdAs = $team->id;
            if ($actionIdAllow = CacheHelper::get(
                    self::KEY_CACHE_PERMISSION_TEAM_ACTION, 
                    $teamIdAs . '_' . $teamMember->role_id)) {
                $actionIdsAllow[$teamIdOrgin] = $actionIdAllow;
            } else {
                //get permission of team member
                $teamPermission = Permissions::select('action_id',  'route', 'scope')
                    ->join($actionTable, $actionTable . '.id', '=', $permissionTable . '.action_id')
                    ->where('team_id', $team->id)
                    ->where('role_id', $teamMember->role_id)
                    ->get();
                foreach ($teamPermission as $item) {
                    if (! $item->scope) {
                        continue;
                    }
                    if ($item->action_id) {
                        $actionIdsAllow[$teamIdOrgin][$item->action_id] = $item->scope;
                    }
                }
                CacheHelper::put(
                    self::KEY_CACHE_PERMISSION_TEAM_ACTION, 
                    $actionIdsAllow[$teamIdOrgin],
                    $teamIdAs . '_' . $teamMember->role_id
                );
            }
            
            //get scope of route name from action id
            if (! isset($actionIdsAllow[$teamIdOrgin]) || ! count($actionIdsAllow[$teamIdOrgin])) {
                continue;
            }
            $actionIds = $actionIdsAllow[$teamIdOrgin];
            $actionIds = array_keys($actionIds);
            if ($routeAllow = CacheHelper::get(
                    self::KEY_CACHE_PERMISSION_TEAM_ROUTE,
                    $teamIdAs . '_' . $teamMember->role_id)) {
                $routesAllow[$teamIdOrgin] = $routeAllow;
                continue;
            }
            $routes = Action::getRouteChildren($actionIds);
            foreach ($routes as $route => $valueIds) {
                if ($valueIds['id'] && isset($actionIdsAllow[$teamIdOrgin][$valueIds['id']])) {
                    $routesAllow[$teamId][$route] = $actionIdsAllow[$teamIdOrgin][$valueIds['id']];
                } else if ($valueIds['parent_id'] && isset($actionIdsAllow[$teamIdOrgin][$valueIds['parent_id']])) {
                    $routesAllow[$teamIdOrgin][$route] = $actionIdsAllow[$teamIdOrgin][$valueIds['parent_id']];
                }
            }
            CacheHelper::put(
                    self::KEY_CACHE_PERMISSION_TEAM_ROUTE, 
                    $routesAllow[$teamIdOrgin],
                    $teamIdAs . '_' . $teamMember->role_id
                );
        }
        
        return [
            'route' => $routesAllow,
            'action' => $actionIdsAllow,
        ];
    }
    
    /**
     * get acl role of rule
     * 
     * @return array
     */
    protected function getPermissionRole()
    {
        $roles = $this->getRoleIds();
        if (! $roles || ! count($roles)) {
            return [];
        }
        $routesAllow = [];
        $actionIdsAllow = [];
        $actionTable = Action::getTableName();
        $permissionTable = Permissions::getTableName();
        foreach ($roles as $role) {
            if ($actionIdAllow = CacheHelper::get(
                    self::KEY_CACHE_PERMISSION_ROLE_ACTION,
                    $role->role_id)) {
                $actionIdsAllow = $actionIdAllow;
            } else {
                $rolePermission = Permissions::select('action_id',  'route', 'scope')
                    ->join($actionTable, $actionTable . '.id', '=', $permissionTable . '.action_id')
                    ->where('team_id', null)
                    ->where('role_id', $role->role_id)
                    ->get();
                foreach ($rolePermission as $item) {
                    if (! $item->scope) {
                        continue;
                    }
                    if ($item->action_id) {
                        //get scope greater of role
                        if (isset($actionIdsAllow[$item->action_id]) && $actionIdsAllow[$item->action_id] > (int) $item->scope) {
                            continue;
                        }
                        $actionIdsAllow[$item->action_id] = (int) $item->scope;
                    }
                }
                CacheHelper::put(
                    self::KEY_CACHE_PERMISSION_ROLE_ACTION, 
                    $actionIdsAllow,
                    $role->role_id
                );
            }
        }
        
        //get scope of route name from action id
        if ($actionIdsAllow) {
            $actionIds = array_keys($actionIdsAllow);
            if (Cache::has($keyCache)) {
                $routesAllow = Cache::get($keyCache);
            } else {
                $routes = Action::getRouteChildren($actionIds);
                foreach ($routes as $route => $valueIds) {
                    if ($valueIds['id'] && isset($actionIdAllow[$valueIds['id']])) {
                        $routesAllow[$route] = $actionIdAllow[$valueIds['id']];
                    } else if ($valueIds['parent_id'] && isset($actionIdAllow[$valueIds['parent_id']])) {
                        $routesAllow[$route] = $actionIdAllow[$valueIds['parent_id']];
                    }
                }
                Cache::put($keyCache, $routesAllow, self::$timeStoreCache);
            }
        }
        
        return [
            'route' => $routesAllow,
            'action' => $actionIdAllow,
        ];
    }
    
    /**
     * gender to option
     * 
     * @return array
     */
    public static function toOptionGender()
    {
        return [
            [
                'value' => self::GENDER_FEMALE,
                'label' => Lang::get('team::view.Female')
            ],
            [
            'value' => self::GENDER_MALE,
            'label' => Lang::get('team::view.Male')
            ]
        ];
    }
    
    /**
     * check employee allow login
     * 
     * @return boolean
     */
    public function isAllowLogin()
    {
        $joinDate = strtotime($this->join_date);
        $leaveDate = strtotime($this->leave_date);
        $nowDate = time();
        if ($joinDate > $nowDate || ($leaveDate && $leaveDate <= $nowDate)) {
            return false;
        }
        return true;
    }
}
