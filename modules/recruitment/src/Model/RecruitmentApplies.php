<?php
namespace Rikkei\Recruitment\Model;

use Rikkei\Core\Model\CoreModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rikkei\Team\Model\Employees;

class RecruitmentApplies extends CoreModel
{
    
    use SoftDeletes;
    
    protected $table = 'recruitment_applies';
    
    /**
     * get presenter name follow phone
     * 
     * @param type $phone
     * @return string
     */
    public static function getPresenterName($phone)
    {
        $employeeTable = Employees::getTableName();
        $recruimentApplyTable = self::getTableName();
        $recruitmentApply = self::select("{$employeeTable}.name as e_name")
            ->join($employeeTable, "{$employeeTable}.id", '=', "{$recruimentApplyTable}.presenter_id")
            ->where('phone', $phone)
            ->first();
        if ($recruitmentApply) {
            return $recruitmentApply->e_name;
        }
        return '';
    }
    
}

