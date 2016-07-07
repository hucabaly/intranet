<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;
use DB;
use Exception;
use Lang;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rikkei\Recruitment\Model\RecruitmentApplies;
use Rikkei\Core\View\CacheHelper;
use Illuminate\Support\Facades\Input;

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
        $collection = self::pagerCollection($collection, $pager['limit'], $pager['page']);
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
        if (! $this->birthday) {
            $this->birthday = null;
        }
        
        DB::beginTransaction();
        try {
            $this->saveCode();
            $this->saveRecruitmentAppyId();
            $result = parent::save($options);
            $this->saveTeamPosition();
            $this->saveRoles();
            $this->saveSkills();
            DB::commit();
            CacheHelper::forget(self::KEY_CACHE);
            return $result;
        } catch (Exception $ex) {
            DB::rollback();
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
        if (! $this->id) {
            return;
        }
        
        if (! $teamPostions) {
            $teamPostions = (array) Input::get('team');
            if (isset($teamPostions[0])) {
                unset($teamPostions[0]);
            }
            if (! Input::get('employee_team_change')) {
                return;
            }
        }
        if (! $teamPostions) {
            return;
        }
        
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
            if (! isset($teamPostions[$i])) {
                continue;
            }
            for ($j = $i + 1 ; $j <= $lengthTeamPostionsSubmit ; $j ++) {
                if (! isset($teamPostions[$j])) {
                    continue;
                }
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

            foreach ($teamPostions as $teamPostion) {
                $team = Team::find($teamPostion['team']);
                if (! $team) {
                    continue;
                }
                if (! $team->isFunction()) {
                    throw new Exception(Lang::get('team::messages.Team :name isnot function', ['name' => $team->name]));
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
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        CacheHelper::forget(self::KEY_CACHE, $this->id);
    }
    
    /**
     * save role for employee
     * 
     * @param array $roles
     * @throws Exception
     */
    public function saveRoles(array $roles = [])
    {
        if (! $this->id) {
            return;
        }
        if (! $roles) {
            $roles = (array) Input::get('role');
            if (! Input::get('employee_role_change')) {
                return;
            }
        }
        
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
    
    protected function saveSkills()
    {
        if (! $this->id) {
            return;
        }
        $skillsAll = Input::all();
        $skills = array_get($skillsAll, 'employee_skill');
        $skillsChage = array_get($skillsAll, 'employee_skill_change');
        if (! $skills || !$skillsChage) {
            return;
        }
        $skillsArray = [];
        $skillsChageArray = [];
        parse_str($skills, $skillsArray);
        parse_str($skillsChage, $skillsChageArray);
        
        //save school
        if (isset($skillsArray['schools'][0])) {
            unset($skillsArray['schools'][0]);
        }
        if (isset($skillsArray['schools']) &&
            isset($skillsChageArray['schools']) && $skillsChageArray['schools']) {
            $this->saveSchools($skillsArray['schools']);
        }
        
        // save language
        if (isset($skillsArray['languages'][0])) {
            unset($skillsArray['languages'][0]);
        }
        if (isset($skillsArray['languages']) &&
            isset($skillsChageArray['languages']) && $skillsChageArray['languages']) {
            $this->saveCetificateType($skillsArray['languages'], Cetificate::TYPE_LANGUAGE);
        }
        
        // save cetificate
        if (isset($skillsArray['cetificates'][0])) {
            unset($skillsArray['cetificates'][0]);
        }
        if (isset($skillsArray['cetificates']) &&
            isset($skillsChageArray['cetificates']) && $skillsChageArray['cetificates']) {
            $this->saveCetificateType($skillsArray['cetificates'], Cetificate::TYPE_CETIFICATE);
        }
        
        // save skill
        if (isset($skillsArray['programs'][0])) {
            unset($skillsArray['programs'][0]);
        }
        if (isset($skillsArray['programs']) &&
            isset($skillsChageArray['programs']) && $skillsChageArray['programs']) {
            $this->saveSkillItem($skillsArray['programs'], Skill::TYPE_PROGRAM);
        }
        
        if (isset($skillsArray['oss'][0])) {
            unset($skillsArray['oss'][0]);
        }
        if (isset($skillsArray['oss']) &&
            isset($skillsChageArray['oss']) && $skillsChageArray['oss']) {
            $this->saveSkillItem($skillsArray['oss'], Skill::TYPE_OS);
        }
        
        if (isset($skillsArray['databases'][0])) {
            unset($skillsArray['databases'][0]);
        }
        if (isset($skillsArray['databases']) &&
            isset($skillsChageArray['databases']) && $skillsChageArray['databases']) {
            $this->saveSkillItem($skillsArray['databases'], Skill::TYPE_DATABASE);
        }
        
        //save work experience
        if (isset($skillsArray['work_experiences'][0])) {
            unset($skillsArray['work_experiences'][0]);
        }
        if (isset($skillsArray['work_experiences']) &&
            isset($skillsChageArray['work_experiences']) && $skillsChageArray['work_experiences']) {
            $this->saveWorkExperience($skillsArray['work_experiences']);
        }
        
        //save project experience
        if (isset($skillsArray['project_experiences'][0])) {
            unset($skillsArray['project_experiences'][0]);
        }
        if (isset($skillsArray['project_experiences']) &&
            isset($skillsChageArray['project_experiences']) && $skillsChageArray['project_experiences']) {
            $this->saveProjectExperience($skillsArray['project_experiences']);
        }        
    }

    /**
     * save schools item
     * 
     * @param array $college
     * @return array
     */
    protected function saveSchools($schools = [])
    {
        $schoolIds = School::saveItems($schools);
        $this->saveEmployeeSchools($schoolIds, $schools);
        
    }
    
    /**
     * save employee school
     * 
     * @param type $schoolIds
     * @param type $schools
     * @return type
     */
    protected function saveEmployeeSchools($schoolIds = [], $schools = [])
    {
        return EmployeeSchool::saveItems($this->id, $schoolIds, $schools);
    }
    
    /**
     * 
     * @param array $cetificatesType
     * @param type $type
     * @return type
     */
    protected function saveCetificateType($cetificatesType = [], $type = null)
    {
        $cetificatesTypeIds = Cetificate::saveItems($cetificatesType, $type);
        $this->saveEmployeeCetificateType($cetificatesTypeIds, $cetificatesType, $type);
    }
    
    /**
     * save employee cetificate
     * 
     * @param type $cetificatesTypeIds
     * @param type $cetificatesType
     * @return type
     */
    protected function saveEmployeeCetificateType($cetificatesTypeIds = [], $cetificatesType = [], $type = null)
    {
        return EmployeeCetificate::saveItems($this->id, $cetificatesTypeIds, $cetificatesType, $type);
    }
    
    /**
     * save skills
     * 
     * @param array $skills
     * @param type $type
     * @return type
     */
    protected function saveSkillItem($skills = [], $type = null)
    {
        $skillIds = Skill::saveItems($skills, $type);
        $this->saveEmployeeSkillItem($skillIds, $skills, $type);
    }
    
    /**
     * save employee skills
     * 
     * @param type $cetificatesTypeIds
     * @param type $cetificatesType
     * @return type
     */
    protected function saveEmployeeSkillItem($skillIds = [], $skills = [], $type = null)
    {
        return EmployeeSkill::saveItems($this->id, $skillIds, $skills, $type);
    }
    
    /**
     * save work experience for employee
     * 
     * @param type $workExperienceData
     * @return type
     */
    protected function saveWorkExperience($workExperienceData)
    {
        return WorkExperience::saveItems($this->id, $workExperienceData);
    }
    
    protected function saveProjectExperience($projectExperienceData)
    {
        return ProjectExperience::saveItems($this->id, $projectExperienceData);
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
        if ($this->id) {
            CacheHelper::put(self::KEY_CACHE, $employeeTeam, $this->id);
        }
        return $employeeTeam;
    }
    
    /**
     * get schools of employee
     * 
     * @return model
     */
    public function getSchools()
    {
        if ($employeeSchools = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeSchools;
        }
        $employeeSchools = EmployeeSchool::getItemsFollowEmployee($this->id);
        CacheHelper::put(self::KEY_CACHE, $employeeSchools, $this->id);
        return $employeeSchools;
    }
    
    /**
     * get language of employee
     * 
     * @return model
     */
    public function getLanguages()
    {
        if ($employeeLanguages = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeLanguages;
        }
        $employeeLanguages = EmployeeCetificate::getItemsFollowEmployee($this->id, Cetificate::TYPE_LANGUAGE);
        CacheHelper::put(self::KEY_CACHE, $employeeLanguages, $this->id);
        return $employeeLanguages;
    }
    
    /**
     * get cetificate of employee
     * 
     * @return model
     */
    public function getCetificates()
    {
        if ($employeeCetificates = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeCetificates;
        }
        $employeeCetificates = EmployeeCetificate::getItemsFollowEmployee($this->id, Cetificate::TYPE_CETIFICATE);
        CacheHelper::put(self::KEY_CACHE, $employeeCetificates, $this->id);
        return $employeeCetificates;
    }
    
    /**
     * get programs of employee
     * 
     * @return model
     */
    public function getPrograms()
    {
        if ($employeeSkills = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeSkills;
        }
        $employeeSkills = EmployeeSkill::getItemsFollowEmployee($this->id, Skill::TYPE_PROGRAM);
        CacheHelper::put(self::KEY_CACHE, $employeeSkills, $this->id);
        return $employeeSkills;
    }
    
    /**
     * get database of employee
     * 
     * @return model
     */
    public function getDatabases()
    {
        if ($employeeSkills = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeSkills;
        }
        $employeeSkills = EmployeeSkill::getItemsFollowEmployee($this->id, Skill::TYPE_DATABASE);
        CacheHelper::put(self::KEY_CACHE, $employeeSkills, $this->id);
        return $employeeSkills;
    }
    
    /**
     * get os of employee
     * 
     * @return model
     */
    public function getOss()
    {
        if ($employeeSkills = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeSkills;
        }
        $employeeSkills = EmployeeSkill::getItemsFollowEmployee($this->id, Skill::TYPE_OS);
        CacheHelper::put(self::KEY_CACHE, $employeeSkills, $this->id);
        return $employeeSkills;
    }
    
    /**
     * get work experience of employee
     * 
     * @return model
     */
    public function getWorkExperience()
    {
        if ($employeeSkills = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeSkills;
        }
        $employeeSkills = WorkExperience::getItemsFollowEmployee($this->id);
        CacheHelper::put(self::KEY_CACHE, $employeeSkills, $this->id);
        return $employeeSkills;
    }
    
    /**
     * get project experience of employee
     * 
     * @return model
     */
    public function getProjectExperience()
    {
        if ($employeeSkills = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $employeeSkills;
        }
        $employeeSkills = ProjectExperience::getItemsFollowEmployee($this->id);
        CacheHelper::put(self::KEY_CACHE, $employeeSkills, $this->id);
        return $employeeSkills;
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
        if ($this->id) {
            CacheHelper::put(self::KEY_CACHE, $employeeRole, $this->id);
        }
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
        if ($this->id) {
            CacheHelper::put(self::KEY_CACHE, $employeeRole, $this->id);
        }
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
            if (! $team->isFunction()) {
                continue;
            }
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
                if (count($teamPermission)) {
                    foreach ($teamPermission as $item) {
                        if (! $item->scope) {
                            continue;
                        }
                        if ($item->action_id) {
                            $actionIdsAllow[$teamIdOrgin][$item->action_id] = $item->scope;
                        }
                    }
                    if (isset($actionIdsAllow[$teamIdOrgin]) && $actionIdsAllow[$teamIdOrgin]) {
                        CacheHelper::put(
                            self::KEY_CACHE_PERMISSION_TEAM_ACTION, 
                            $actionIdsAllow[$teamIdOrgin],
                            $teamIdAs . '_' . $teamMember->role_id
                        );
                    }
                }
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
            if (count($routes)) {
                foreach ($routes as $route => $valueIds) {
                    if ($valueIds['id'] && isset($actionIdsAllow[$teamIdOrgin][$valueIds['id']])) {
                        $routesAllow[$teamId][$route] = $actionIdsAllow[$teamIdOrgin][$valueIds['id']];
                    } else if ($valueIds['parent_id'] && isset($actionIdsAllow[$teamIdOrgin][$valueIds['parent_id']])) {
                        $routesAllow[$teamIdOrgin][$route] = $actionIdsAllow[$teamIdOrgin][$valueIds['parent_id']];
                    }
                }
                if (isset($routesAllow[$teamIdOrgin]) && $routesAllow[$teamIdOrgin]) {
                    CacheHelper::put(
                            self::KEY_CACHE_PERMISSION_TEAM_ROUTE, 
                            $routesAllow[$teamIdOrgin],
                            $teamIdAs . '_' . $teamMember->role_id
                        );
                }
            }
        }
        
        if (! $routesAllow && ! $actionIdsAllow) {
            return [];
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
        $routesAllowOfRole = [];
        $actionIdsAllowOfRole = [];
        $actionTable = Action::getTableName();
        $permissionTable = Permissions::getTableName();
        foreach ($roles as $role) {
            if ($actionIdAllow = CacheHelper::get(
                    self::KEY_CACHE_PERMISSION_ROLE_ACTION,
                    $role->role_id)) {
                $actionIdsAllowOfRole = $actionIdAllow;
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
                        $actionIdsAllowOfRole[$item->action_id] = (int) $item->scope;
                    }
                }
                CacheHelper::put(
                    self::KEY_CACHE_PERMISSION_ROLE_ACTION, 
                    $actionIdsAllowOfRole,
                    $role->role_id
                );
            }
            
            //get scope of route name from action id
            if ($actionIdsAllowOfRole) {
                if ($routeAllow = CacheHelper::get(
                    self::KEY_CACHE_PERMISSION_ROLE_ROUTE,
                    $role->role_id)) {
                    $routesAllowOfRole = $routeAllow;
                } else {
                    $actionIds = array_keys($actionIdsAllowOfRole);
                    $routes = Action::getRouteChildren($actionIds);
                    foreach ($routes as $route => $valueIds) {
                        if ($valueIds['id'] && isset($actionIdsAllowOfRole[$valueIds['id']])) {
                            $routesAllowOfRole[$route] = $actionIdsAllowOfRole[$valueIds['id']];
                        } else if ($valueIds['parent_id'] && isset($actionIdsAllowOfRole[$valueIds['parent_id']])) {
                            $routesAllowOfRole[$route] = $actionIdsAllowOfRole[$valueIds['parent_id']];
                        }
                    }
                    CacheHelper::put(
                            self::KEY_CACHE_PERMISSION_ROLE_ROUTE, 
                            $routesAllowOfRole, 
                            $role->role_id
                    );
                }
            }
            
            //get scope greater of role for user
            foreach ($actionIdsAllowOfRole as $actionId => $scope) {
                if (isset($actionIdsAllow[$actionId]) && $actionIdsAllow[$actionId] > $scope) {
                    continue;
                }
                $actionIdsAllow[$actionId] = $scope;
            }
            
            foreach ($routesAllowOfRole as $route => $scope) {
                if (isset($routesAllow[$route]) && $routesAllow[$route] > $scope) {
                    continue;
                }
                $routesAllow[$route] = $scope;
            }
        }
        
        return [
            'route' => $routesAllow,
            'action' => $actionIdsAllow,
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
    
    /**
     * check is leader of a team
     * 
     * @return boolean
     */
    public function isLeader()
    {
        if ($employeeLeader = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return self::flagToBoolean($employeeLeader);
        }
        $positions = $this->getTeamPositons();
        foreach ($positions as $position) {
            $employeeLeader = Roles::isPositionLeader($position->role_id);
            if ($employeeLeader) {
                break;
            }
        }
        CacheHelper::put(
            self::KEY_CACHE, 
            self::booleanToFlag($employeeLeader), 
            $this->id);
        return $employeeLeader;
    }
    
    /**
     * check permission greater with another employee
     * 
     * @param model $employee
     * @param boolean $checkIsLeader
     * @return boolean
     */
    public function isGreater($employee, $checkIsLeader = false)
    {
        if ($employeeGreater = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return self::flagToBoolean($employeeGreater);
        }
        if (is_numeric($employee)) {
            $employee = Employees::find($employee);
        }
        $employeeGreater = null;
        if (! $employee) {
            $employeeGreater = false;
        } elseif ($this->id == $employee->id) {
            if ($checkIsLeader) {
                if ($this->isLeader()) {
                    $employeeGreater = true;
                } else {
                    $employeeGreater = false;
                }
            } else {
                $employeeGreater = false;
            }
        } else {
            $thisTeam = $this->getTeamPositons();
            $anotherTeam = $employee->getTeamPositons();
            $teamPaths = Team::getTeamPath();
            if (! count($thisTeam) || ! count($anotherTeam) || ! count($teamPaths)) {
                $employeeGreater = false;
            }
        }
        if ($employeeGreater === null) {
            $employeeGreater = $this->isTeamPositionGreater(
                $thisTeam, 
                $anotherTeam, 
                $teamPaths,
                $checkIsLeader
            );
        }
        CacheHelper::put(self::KEY_CACHE, self::booleanToFlag($employeeGreater), $this->id);
        return $employeeGreater;
    }
    
    /**
     * check team greater, position greater of 2 employee
     * 
     * @param model $thisTeam
     * @param model $anotherTeam
     * @param array $teamPaths
     * @return boolean
     */
    protected function isTeamPositionGreater(
        $thisTeam, 
        $anotherTeam, 
        $teamPaths, 
        $checkIsLeader = false
    ) {
        foreach ($anotherTeam as $anotherTeamItem) {
            foreach ($thisTeam as $thisTeamItem) {
                // this team is team root
                if (! $teamPaths[$anotherTeamItem->team_id]) {
                    continue;
                }
                // team greater
                if (in_array($thisTeamItem->team_id, $teamPaths[$anotherTeamItem->team_id])) {
                    return true;
                }
                // 2 team diffirent branch
                if ($thisTeamItem->team_id != $anotherTeamItem->team_id) {
                    continue;
                }
                // same team, compare position
                $thisPosition = Roles::find($thisTeamItem->role_id);
                $anotherPosition = Roles::find($anotherTeamItem->role_id);
                if (! $thisPosition || 
                    ! $anotherPosition || 
                    ! $thisPosition->isPosition() || 
                    ! $anotherPosition->isPosition()) {
                    continue;
                }
                if ($checkIsLeader) {
                    if ($thisPosition->isLeader()) {
                        return true;
                    }
                    return false;
                }
                if ($thisPosition->sort_order < $anotherPosition->sort_order) {
                    return true;
                }
            }
        }
        return false;
    }
}
