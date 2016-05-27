<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\User as BaseUser;

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
    public function getTeam()
    {
        return Team::find($this->team_id);
    }
    
    /**
     * get position of user
     * 
     * @return model
     */
    public function getPosition()
    {
        return Position::find($this->position_id);
    }
    
    /**
     * get acl of user
     * acl is array route name allowed
     * 
     * @return array
     */
    public function getAcl()
    {
        $team = $this->getTeam();
        $position = $this->getPosition();
        if (! $team || ! $position) {
            return [];
        }
        $teamAs = $team->getTeamPermissionAs();
        if ($teamAs) {
            $team = $teamAs;
        }
        $teamRule = TeamRule::select('rule', 'scope')
            ->where('team_id', $team->id)
            ->where('position_id', $position->id)
            ->get();
        $routesAllow = [];
        foreach ($teamRule as $item) {
            $routes = \Rikkei\Team\View\Acl::getRoutesNameFromKey($item->rule);
            if (! $routes) {
                continue;
            }
            foreach ($routes as $route) {
                $routesAllow[$route] = $item->scope;
            }
        }
        return $routesAllow;
    }
}
