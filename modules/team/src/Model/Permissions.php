<?php
namespace Rikkei\Team\Model;

use DB;
use Lang;
use Cache;

class Permissions extends \Rikkei\Core\Model\CoreModel
{
    const SCOPE_NONE = 0;
    const SCOPE_SELF = 1;
    const SCOPE_TEAM = 2;
    const SCOPE_COMPANY = 3;
 
    protected $table = 'permissions';
    
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
        $scopeIcon = self::scopeIconArray();
        return [
            ['value' => self::SCOPE_NONE, 'label' => $scopeIcon[self::SCOPE_NONE]],
            ['value' => self::SCOPE_SELF, 'label' => $scopeIcon[self::SCOPE_SELF]],
            ['value' => self::SCOPE_TEAM, 'label' => $scopeIcon[self::SCOPE_TEAM]],
            ['value' => self::SCOPE_COMPANY, 'label' => $scopeIcon[self::SCOPE_COMPANY]],
        ];
    }
    
    /**
     * get scope format icon
     * 
     * @return array
     */
    public static function scopeIconArray()
    {
        return [
            self::SCOPE_NONE => '',
            self::SCOPE_SELF => '<i class="fa fa-times"></i>',
            self::SCOPE_TEAM => '<i class="fa fa-caret-up"></i>',
            self::SCOPE_COMPANY => '<i class="fa fa-circle-o"></i>',
        ];
    }
    
    /**
     * get scope guide follow icon
     * 
     * @return string
     */
    public static function getScopeIconGuide()
    {
        $scopesLabel = [
            self::SCOPE_NONE => 'none permission',
            self::SCOPE_SELF => 'self',
            self::SCOPE_TEAM => 'their management team',
            self::SCOPE_COMPANY => 'company',
        ];
        $html = '<p>' . Lang::get('team::view.Note') . ':</p>';
        $html .= '<ul>';
        $scopeIcon = self::scopeIconArray();
        foreach ($scopesLabel as $scopeValue =>$scopeLabel) {
            $html .= '<li>';
            $html .= '<b>' . $scopeIcon[$scopeValue] . '</b>: ';
            $html .= '<span>' . Lang::get('team::view.Scope '. $scopeLabel) . '</span>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    
    /**
     * save teamrule
     * 
     * @param array $data
     * @param int $teamId
     * @return type
     */
    public static function saveRule(array $data, $teamId, $addTeamId = true) {
        if (! $data || ! $teamId) {
            return;
        }
        if ($addTeamId) {
            foreach ($data as &$item) {
                $item['team_id'] = $teamId;
            }
        }
        
        DB::beginTransaction();
        try {
            self::where('team_id', $teamId)->delete();
            self::insert($data);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
}
