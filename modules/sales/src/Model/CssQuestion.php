<?php

namespace Rikkei\Sales\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CssQuestion extends Model
{
    
    use SoftDeletes;
    
    protected $table = 'css_question';
    
    /**
     * Get overview question by category
     * @param ing $categoryId
     * @param int $isOverviewQuestion
     */
    public function getOverviewQuestionByCategory($categoryId, $isOverviewQuestion = 1){
        return self::where("category_id",$categoryId)->where('is_overview_question',$isOverviewQuestion)->first();
    }
    
    /**
     * Get questions list by category
     * @param int $categoryId
     * @return object list questions
     */
    public function getQuestionByCategory($categoryId){
        return self::where('category_id', $categoryId)->withTrashed()->get();
    }
}
