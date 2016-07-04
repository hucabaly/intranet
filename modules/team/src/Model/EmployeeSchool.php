<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Core\View\View;

class EmployeeSchool extends CoreModel
{
    const KEY_CACHE = 'employee_school';

    protected $table = 'employee_schools';
    public $timestamps = false;

    /**
     * save employee school
     * 
     * @param int $employeeId
     * @param array $schoolIds
     * @param array $employeeSchools
     */
    public static function saveItems($employeeId, $schoolIds= [], $employeeSchools = [])
    {
        if (! $schoolIds || ! $employeeSchools || ! $employeeId) {
            return;
        }
        self::where('employee_id', $employeeId)->delete();
        foreach ($schoolIds as $key => $schoolId) {
            if (! isset($employeeSchools[$key])) {
                continue;
            }
            $employeeSchoolItem = new self();
            $employeeSchoolItem->setData($employeeSchools[$key]);
            $employeeSchoolItem->school_id = $schoolId;
            $employeeSchoolItem->employee_id = $employeeId;
            $employeeSchoolItem->updated_at = date('Y-m-d H:i:s');
            if (! $employeeSchoolItem->start_at) {
                $employeeSchoolItem->start_at = null;
            }
            if (! $employeeSchoolItem->end_at) {
                $employeeSchoolItem->end_at = null;
            }
            $employeeSchoolItem->save();
        }
    }
    
    /**
     * 
     * @param type $employeeId
     * @return object model
     */
    public static function getItemsFollowEmployee($employeeId)
    {
        $thisTable = self::getTableName();
        $schoolTable = School::getTableName();
        
        return self::select('school_id', 'start_at', 'end_at', 'majors', 
                'name', 'country', 'province', 'image')
            ->join($schoolTable, "{$schoolTable}.id", '=', "{$thisTable}.school_id")
            ->where('employee_id', $employeeId)
            ->get();
    }
}
