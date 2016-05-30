<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\User as BaseUser;
use Rikkei\Team\View\Acl;

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
            ->where('user_id', $this->id)
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
        $teams = $this->getTeams();
        if (! $teams || ! count($teams)) {
            return [];
        }
        $routesAllow = [];
        foreach ($teams as $teamMember) {
            $team = Team::find($teamMember->team_id);
            $position = Position::find($teamMember->position_id);
            $teamAs = $team->getTeamPermissionAs();
            if ($teamAs) {
                $team = $teamAs;
            }
            $teamRule = TeamRule::select('rule', 'scope')
                ->where('team_id', $team->id)
                ->where('position_id', $position->id)
                ->get();
            foreach ($teamRule as $item) {
                $routes = Acl::getRoutesNameFromKey($item->rule);
                if (! $routes) {
                    continue;
                }
                foreach ($routes as $route) {
                    $routesAllow['team'][$team->id][$route] = $item->scope;
                }
            }
        }
        return $routesAllow;
    }
}
