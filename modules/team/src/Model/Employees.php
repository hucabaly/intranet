<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;
use DB;
use Exception;
use Lang;

class Employees extends CoreModel
{
    
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
        $collection = self::select('id','name','email')
            ->orderBy($pager['order'], $pager['dir']);
        $collection = self::filterGrid($collection);
        $collection = $collection->paginate($pager['limit']);
        return $collection;
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
            TeamMembers::where('employee_id', $this->id)->delete();
            if (count($teamPostions)) {
                foreach ($teamPostions as $teamPostion) {
                    $teamMember = new TeamMembers();
                    $teamMember->setData([
                        'team_id' => $teamPostion['team'],
                        'role_id' => $teamPostion['position'],
                        'employee_id' => $this->id
                    ]);
                    $teamMember->save();
                }
            }
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
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * get team and position of employee
     * 
     * @return collection
     */
    public function getTeamPositons()
    {
        return TeamMembers::select('team_id', 'role_id')->where('employee_id', $this->id)->get();
    }
    
    /**
     * get roles of employee
     * 
     * @return collection
     */
    public function getRoles()
    {
        return EmployeeRole::select('role_id', 'role')
                ->join('roles', 'roles.id', '=', 'employee_roles.role_id')
                ->where('employee_id', $this->id)
                ->orderBy('role')
                ->get();
    }
    
    /**
     * get roles of employee
     * 
     * @return collection
     */
    public function getRoleIds()
    {
        return EmployeeRole::select('role_id')
            ->where('employee_id', $this->id)
            ->get();
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
        $actionIdAllow = [];
        $actionTable = Action::getTableName();
        $permissionTable = Permissions::getTableName();
        foreach ($teams as $teamMember) {
            $team = Team::find($teamMember->team_id);
            $teamAs = $team->getTeamPermissionAs();
            $teamIdOrgin = $team->id;
            if ($teamAs) {
                $team = $teamAs;
            }
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
                    $actionIdAllow[$teamIdOrgin][$item->action_id] = $item->scope;
                }
            }
        }
        //get scope of route name from action id
        if ($actionIdAllow && count($actionIdAllow)) {
            foreach ($actionIdAllow as $teamId => $actionIds) {
                $actionIds = array_keys($actionIds);
                $routes = Action::getRouteChildren($actionIds);
                foreach ($routes as $route => $valueIds) {
                    if ($valueIds['id'] && isset($actionIdAllow[$teamId][$valueIds['id']])) {
                        $routesAllow[$teamId][$route] = $actionIdAllow[$teamId][$valueIds['id']];
                    } else if ($valueIds['parent_id'] && isset($actionIdAllow[$teamId][$valueIds['parent_id']])) {
                        $routesAllow[$teamId][$route] = $actionIdAllow[$teamId][$valueIds['parent_id']];
                    }
                }
            }
        }
        return [
            'route' => $routesAllow,
            'action' => $actionIdAllow,
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
        $actionIdAllow = [];
        $actionTable = Action::getTableName();
        $permissionTable = Permissions::getTableName();
        foreach ($roles as $role) {
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
                    if (isset($actionIdAllow[$item->action_id]) && $actionIdAllow[$item->action_id] > (int) $item->scope) {
                        continue;
                    } 
                    $actionIdAllow[$item->action_id] = (int) $item->scope;
                }
            }
        }
        if ($actionIdAllow) {
            $actionIds = array_keys($actionIdAllow);
            $routes = Action::getRouteChildren($actionIds);
            foreach ($routes as $route => $valueIds) {
                if ($valueIds['id'] && isset($actionIdAllow[$valueIds['id']])) {
                    $routesAllow[$route] = $actionIdAllow[$valueIds['id']];
                } else if ($valueIds['parent_id'] && isset($actionIdAllow[$valueIds['parent_id']])) {
                    $routesAllow[$route] = $actionIdAllow[$valueIds['parent_id']];
                }
            }
        }
        return [
            'route' => $routesAllow,
            'action' => $actionIdAllow,
        ];
    }
}
