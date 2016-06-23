<?php

namespace Rikkei\Sales\Model;

use Illuminate\Database\Eloquent\Model;
use Rikkei\Sales\Model\CssQuestion;

class CssCategory extends Model
{
    protected $table = 'css_category';
    
    /**
     * Get root category by project type
     * @param int $projectTypeId
     * @return object category
     */
    public function getRootCategory($projectTypeId){
        return self::where("parent_id",0)->where('project_type_id',$projectTypeId)->first();
    }
    
    /**
     * Get category by parent
     * @param int $parentId
     * @return object list category
     */
    public function getCategoryByParent($parentId){
        return self::where('parent_id', $parentId)->get();
    }
    
    /**
     * Get category by question
     * @param int $questionId
     */
    public function getCateByQuestion($questionId){
        return self::where('id', function($query) use ($questionId){
                    $query->select('category_id')
                    ->from(with(new CssQuestion)->getTable())
                    ->where('id', $questionId);
                })->first();
    }    
}
