<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\User as BaseUser;
use Rikkei\Team\View\Acl;
use Illuminate\Support\Facades\Cache;

class User extends BaseUser
{
    //member new default belong
    const TEAM_DEFAULT = 'Temp';
    const POSITION_DEFAULT = 'Member';
    
    /**
     * create team and position default for new member
     * 
     * @return array
     */
    public static function createTeamPositionDefault()
    {
        $dataTeam = [
            'name' => self::TEAM_DEFAULT,
            'parent_id' => 0,
            'position'=> 1,
            'permission_as' => '0',
            'is_function' => ''
        ];
        $team = Team::select('id')
            ->where('name', $dataTeam['name'])
            ->where('parent_id', $dataTeam['parent_id'])
            ->first();
        if (! count($team)) {
            $team = Team::create($dataTeam);
        }
        
        $dataPosition = [
            'name' => self::POSITION_DEFAULT,
            'level' => 3
        ];
        $position = Position::select('id')
            ->where('name', $dataPosition['name'])
            ->first();
        if (! count($position)) {
            $position = Position::create($dataPosition);
        }
        return [
            'team' => $team,
            'position' => $position,
        ];
    }
    
    /**
     * get team of user
     * 
     * @return model
     */
    public function getTeams()
    {
        return TeamMembers::select('team_id', 'position_id')
            ->where('employee_id', $this->employee_id)
            ->get();
    }
    
    public function getRoles()
    {
        return EmployeeRole::select('role_id')
            ->where('employee_id', $this->employee_id)
            ->get();
    }
    
    /**
     * get acl of user
     * acl is array route name allowed follow each team
     * 
     * @return array
     */
    public function getAcl()
    {
        $aclTeam = $this->getAclTeam();
        $aclRole = $this->getAclRole();
        $result = [];
        if ($aclTeam) {
            $result['team'] = $aclTeam;
        }
        if ($aclRole) {
            $result['role'] = $aclRole;
        }
        return $result;
    }
    
    /**
     * get Acl team of user
     * 
     * @return array
     */
    protected function getAclTeam()
    {
        $teams = $this->getTeams();
        if (! $teams || ! count($teams)) {
            return [];
        }
        $routesAllow = [];
        foreach ($teams as $teamMember) {
            $team = Team::find($teamMember->team_id);
            $teamAs = $team->getTeamPermissionAs();
            $teamIdOrgin = $team->id;
            if ($teamAs) {
                $team = $teamAs;
            }
            $teamRule = TeamRule::select('rule', 'scope')
                ->where('team_id', $team->id)
                ->where('position_id', $teamMember->position_id)
                ->get();
            foreach ($teamRule as $item) {
                if (! $item->scope) {
                    continue;
                }
                $routes = Acl::getRoutesNameFromKey($item->rule);
                if (! $routes) {
                    continue;
                }
                foreach ($routes as $route) {
                    $routesAllow[$teamIdOrgin][$route] = $item->scope;
                }
            }
        }
        return $routesAllow;
    }
    
    /**
     * get acl role of rule
     * 
     * @return array
     */
    protected function getAclRole()
    {
        $roles = $this->getRoles();
        if (! $roles) {
            return [];
        }
        $routesAllow = [];
        foreach ($roles as $role) {
            $roleRule = RoleRule::select('rule', 'scope')
                ->where('role_id', $role->role_id)
                ->get();
            if (! $roleRule || ! count($roleRule)) {
                continue;
            }
            foreach ($roleRule as $item) {
                if (! $item->scope) {
                    continue;
                }
                $routes = Acl::getRoutesNameFromKey($item->rule);
                if (! $routes) {
                    continue;
                }
                foreach ($routes as $route) {
                    $routesAllow[$role->role_id][$route] = $item->scope;
                }
            }
        }
        return $routesAllow;
    }
}
