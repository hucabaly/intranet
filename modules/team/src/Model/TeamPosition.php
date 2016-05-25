<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;

class TeamPosition extends CoreModel
{
    protected $table = 'team_position';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'team_id', 'level'
    ];
    /**
     * get team of position
     * 
     * @return model
     */
    public function getTeam()
    {
        return Team::find($this->team_id);
    }
}
