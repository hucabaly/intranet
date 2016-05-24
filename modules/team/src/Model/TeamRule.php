<?php
namespace Rikkei\Team\Model;

class TeamRule extends \Illuminate\Database\Eloquent\Model
{
    const SCOPE_NONE = 0;
    const SCOPE_SELF = 1;
    const SCOPE_TEAM = 2;
    const SCOPE_COMPANY = 3;
 
    protected $table = 'team_rule';
    
    /**
     * get all scope
     * 
     * @return array
     */
    public static function getScopes()
    {
        return [
            'none' => self::SCOPE_NONE,
            'self' => self::SCOPE_SELF,
            'team' => self::SCOPE_TEAM,
            'company' => self::SCOPE_COMPANY,
        ];
    }
    
    /**
     * get scope to assign default
     * 
     * @return int
     */
    public static function getScopeDefault()
    {
        return self::SCOPE_NONE;
    }
    
    /**
     * get scope format option
     * 
     * @return array
     */
    public static function toOption()
    {
        return [
            ['value' => self::SCOPE_NONE, 'label' => 'None'],
            ['value' => self::SCOPE_SELF, 'label' => 'Self'],
            ['value' => self::SCOPE_TEAM, 'label' => 'Team'],
            ['value' => self::SCOPE_COMPANY, 'label' => 'Company'],
        ];
    }
}