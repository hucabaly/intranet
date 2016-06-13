<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends CoreModel
{
    
    use SoftDeletes;
    
    protected $table = 'actions';
    
    /**
     * get action list
     * 
     * @return array
     */
    public static function getListData()
    {
        $actionTree = [];
        //$actions = self::getTreeListRecursive(null, $actionTree);
        $actionsGroup = self::select('id', 'description')
            ->where('parent_id', null)
            ->orderBy('sort_order')
            ->get();
        if (! count($actionsGroup)) {
            return;
        }
        foreach ($actionsGroup as $actionGroup) {
            $actionTree[$actionGroup->id] = [
                'description' => $actionGroup->description,
            ];
            $actionsGroupChild = self::select('id', 'description')
                ->where('parent_id', $actionGroup->id)
                ->orderBy('sort_order')
                ->get();
            if (! count($actionsGroupChild)) {
                continue;
            }
            foreach ($actionsGroupChild as $actionGroupChild) {
                $actionTree[$actionGroup->id]['child'][$actionGroupChild->id] = [
                    'description' => $actionGroupChild->description,
                ];
            }
        }
        return $actionTree;
    }
}
