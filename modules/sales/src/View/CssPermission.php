<?php
namespace Rikkei\Sales\View;

use Auth;
use Rikkei\Sales\Model\Css;
use Rikkei\Sales\Model\CssTeams;
use Rikkei\Team\Model\TeamMembers;
use Rikkei\Team\View\Permission;

/**
 * class permission
 * 
 * check permssion auth
 */
class CssPermission
{
    public static function GetCssListByPermission($perPage){
        $userAccount = Auth::user(); 
        $permission = new Permission();
        $model = new Css();
        
        if($permission->isScopeSelf()){
            $css = $model->getCssListByEmployee($userAccount->employee_id,$perPage);
        }elseif ($permission->isScopeTeam()) {
            $arrTeamId = self::getArrTeamIdByEmployee($userAccount->employee_id);
            $css = $model->getCssListByCssIdAndArrTeamId($arrTeamId, $perPage);
        }elseif ($permission->isScopeCompany()){
            $css = $model->getCssList($perPage);
        }
        
        return $css;
    }
    
    /**
     * Get Css detail permission
     * @param int $cssId
     * @param int $employeeId
     * @return boolean
     */
    public static function isCssPermission($cssId, $employeeId){
        $permission = new Permission();
        $permissionFlag = true;
        
        if($permission->isScopeSelf()){
            $permissionFlag = self::isCssSelf($employeeId);
        }elseif ($permission->isScopeTeam()) {
            $permissionFlag = self::isCssTeam($cssId);
        }elseif ($permission->isScopeNone()){
            $permissionFlag = false;
        }
        
        return $permissionFlag;
    }


    /**
     * Check Css of self
     * @param int $employeeId
     * @return boolean
     */
    protected static function isCssSelf($employeeId){
        $userAccount = Auth::user(); 
        return ($employeeId == $userAccount->employee_id);
    }
    
    /**
     * Check Css of self team
     * @param int $cssId
     * @param int $employeeId
     * @return boolean
     */
    protected static function isCssTeam($cssId){
        $userAccount = Auth::user(); 
        $arrTeamId = self::getArrTeamIdByEmployee($userAccount->employee_id);
        
        if(count($arrTeamId)){
            //Get CssTeam by teams
            $cssTeamModel = new CssTeams();
            $cssTeams = $cssTeamModel->getCssTeamByCssIdAndTeamIds($cssId, $arrTeamId);

            //Check is css team
            if(count($cssTeams)){
                return true; //is css of self team
            }
        }
        
        return false; //is not css of self team
    }    
    
    protected static function getArrTeamIdByEmployee($employee_id){
        $teamMembersModel = new TeamMembers();
        $teamMembers = $teamMembersModel->getTeamMembersByEmployee($employee_id);
        
        //get teams of current user
        $arrTeamId = [];
        foreach($teamMembers as $item){
            $arrTeamId[] = $item->team_id;
        }
        
        return $arrTeamId;
    }
}
