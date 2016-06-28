<?php
namespace Rikkei\Team\Model;

class TeamMembers extends \Rikkei\Core\Model\CoreModel
{
    protected $table = 'team_members';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id', 'employee_id', 'position_id'
    ];
    
    /**
     * Get TeamMembers by employee
     * @param int $employeeId
     * @return TeamMembers list
     */
    public function getTeamMembersByEmployee ($employeeId){
        return self::where('employee_id',$employeeId)->get();
    }
}
