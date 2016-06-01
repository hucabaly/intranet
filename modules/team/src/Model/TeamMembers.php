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
}
