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
}
