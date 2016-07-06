<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Illuminate\Support\Facades\Validator;

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
     * @param array $schools
     */
    public static function saveItems($employeeId, $schoolIds= [], $schools = [])
    {
        if (! $schoolIds || ! $schools || ! $employeeId) {
            return;
        }
        self::where('employee_id', $employeeId)->delete();
        $schoolIdsAdded = [];
        foreach ($schoolIds as $key => $schoolId) {
            if (! isset($schools[$key]) || ! $schools[$key]['employee_school'] || in_array($schoolId, $schoolIdsAdded)) {
                continue;
            }
            $employeeSchoolData = $schools[$key]['employee_school'];
            $validator = Validator::make($employeeSchoolData, [
                'majors' => 'required|max:255',
                'start_at' => 'required|max:255',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->send();
            }
            if (! $employeeSchoolData['end_at']) {
                unset($employeeSchoolData['end_at']);
            }
            $employeeSchoolItem = new self();
            $employeeSchoolItem->setData($employeeSchoolData);
            $employeeSchoolItem->school_id = $schoolId;
            $employeeSchoolItem->employee_id = $employeeId;
            $employeeSchoolItem->updated_at = date('Y-m-d H:i:s');
            $employeeSchoolItem->save();
            $schoolIdsAdded[] = $schoolId;
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
                'name', 'country', 'province', 'image', 'id')
            ->join($schoolTable, "{$schoolTable}.id", '=', "{$thisTable}.school_id")
            ->where('employee_id', $employeeId)
            ->get();
    }
}
