<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Illuminate\Support\Facades\Validator;

class EmployeeSkill extends CoreModel
{
    const KEY_CACHE = 'employee_skill';

    protected $table = 'employee_skills';
    public $timestamps = false;

    /**
     * save employee skill
     * 
     * @param int $employeeId
     * @param array $skillIds
     * @param array $skills
     * @param int $type
     */
    public static function saveItems($employeeId, $skillIds = [], $skills = [], $type = null)
    {
        if (! $type) {
            $type = Skill::TYPE_PROGRAM;
        }
        $skillTable = Skill::getTableName();
        self::where('employee_id', $employeeId)
            ->whereIn('skill_id', function ($query) use ($skillTable, $type) {
                $query->from($skillTable)
                    ->select('id')
                    ->where('type', $type);
            })
            ->delete();
            
        if (! $skills || ! $skillIds || ! $employeeId) {
            return;
        }
        $skillAdded = [];
        
        $typeSkills = Skill::getAllType();
        $tblName = $typeSkills[$type];
        foreach ($skillIds as $key => $skillId) {
            if (! isset($skills[$key]) || 
                ! isset($skills[$key]["employee_{$tblName}"]) ||
                ! $skills[$key]["employee_{$tblName}"] || 
                in_array($skillId, $skillAdded)) {
                continue;
            }
            $employeeSkillData = $skills[$key]["employee_{$tblName}"];
            $arrayRule = [
                'level' => 'required|max:255',
                'experience' => 'required|max:255',
            ];
            $validator = Validator::make($employeeSkillData, $arrayRule);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->send();
            }
            $employeeSkillItem = new self();
            $employeeSkillItem->setData($employeeSkillData);
            $employeeSkillItem->skill_id = $skillId;
            $employeeSkillItem->employee_id = $employeeId;
            $employeeSkillItem->updated_at = date('Y-m-d H:i:s');
            $employeeSkillItem->save();
            $skillAdded[] = $skillId;
        }
    }
    
    /**
     * get skills follow employee
     * 
     * @param type $employeeId
     * @return object model
     */
    public static function getItemsFollowEmployee($employeeId, $type = null)
    {
        if ($type == null ) {
            $type = Skill::TYPE_PROGRAM;
        }
        $thisTable = self::getTableName();
        $skillTable = Skill::getTableName();
        
        return self::select('level', 'experience', 'name', 'image', 'id')
            ->join($skillTable, "{$skillTable}.id", '=', "{$thisTable}.skill_id")
            ->where('employee_id', $employeeId)
            ->where('type', $type)
            ->get();
    }
}
