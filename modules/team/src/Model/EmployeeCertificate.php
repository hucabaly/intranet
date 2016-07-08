<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Illuminate\Support\Facades\Validator;

class EmployeeCertificate extends CoreModel
{
    const KEY_CACHE = 'employee_cetificate';

    protected $table = 'employee_certificates';
    public $timestamps = false;

    /**
     * save employee cetificate
     * 
     * @param int $employeeId
     * @param array $cetificatesTypeIds
     * @param array $cetificatesType
     * @param int $type
     */
    public static function saveItems($employeeId, $cetificatesTypeIds = [], $cetificatesType = [], $type = null)
    {
        if (! $type) {
            $type = Certificate::TYPE_LANGUAGE;
        }
        $cetificateTable = Certificate::getTableName();
        self::where('employee_id', $employeeId)
            ->whereIn('certificate_id', function ($query) use ($cetificateTable, $type) {
                $query->from($cetificateTable)
                    ->select('id')
                    ->where('type', $type);
            })
            ->delete();

        if (! $cetificatesTypeIds || ! $cetificatesType || ! $employeeId) {
            return;
        }
        $cetificateAdded = [];
        
        $typeCetificates = Certificate::getAllType();
        $tblName = $typeCetificates[$type];
        foreach ($cetificatesTypeIds as $key => $cetificatesTypeId) {
            if (! isset($cetificatesType[$key]) || 
                ! isset($cetificatesType[$key]["employee_{$tblName}"]) || 
                ! $cetificatesType[$key]["employee_{$tblName}"] || 
                in_array($cetificatesTypeId, $cetificateAdded)) {
                continue;
            }
            $employeeCetificateData = $cetificatesType[$key]["employee_{$tblName}"];
            if ($type == Certificate::TYPE_LANGUAGE) {
                $arrayRule = [
                    'level' => 'required|max:255',
                    'start_at' => 'required|max:255',
                ];
            } else {
                $arrayRule = [
                    'start_at' => 'required|max:255',
                ];
            }
            $validator = Validator::make($employeeCetificateData, $arrayRule);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->send();
            }
            if ( isset($employeeCetificateData['end_at']) && ! $employeeCetificateData['end_at']) {
                unset($employeeCetificateData['end_at']);
            }
            $employeeCetificateItem = new self();
            $employeeCetificateItem->setData($employeeCetificateData);
            $employeeCetificateItem->certificate_id = $cetificatesTypeId;
            $employeeCetificateItem->employee_id = $employeeId;
            $employeeCetificateItem->updated_at = date('Y-m-d H:i:s');
            $employeeCetificateItem->save();
            $cetificateAdded[] = $cetificatesTypeId;
        }
    }
    
    /**
     * get language follow employee
     * 
     * @param type $employeeId
     * @return object model
     */
    public static function getItemsFollowEmployee($employeeId, $type = null)
    {
        if ($type == null ) {
            $type = Certificate::TYPE_LANGUAGE;
        }
        $thisTable = self::getTableName();
        $cetificateTable = Certificate::getTableName();
        
        return self::select('level', 'start_at', 'end_at', 'type', 
                'name', 'image', 'id')
            ->join($cetificateTable, "{$cetificateTable}.id", '=', "{$thisTable}.certificate_id")
            ->where('employee_id', $employeeId)
            ->where('type', $type)
            ->get();
    }
}