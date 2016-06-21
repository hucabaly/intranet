<?php

namespace Rikkei\Sales\Model;

use Illuminate\Database\Eloquent\Model;

class CssQuestion extends Model
{
    protected $table = 'css_question';
    
    /**
     * Get comment overview question 
     * @param ing $categoryId
     * @param int $isOverviewQuestion
     */
    public function getCommentOverview($categoryId, $isOverviewQuestion = 1){
        return self::where("category_id",$categoryId)->where('is_overview_question',$isOverviewQuestion)->first();
    }
    
    /**
     * Get questions list by category
     * @param int $categoryId
     * @return object list questions
     */
    public function getQuestionByCategory($categoryId){
        return self::where('category_id', $categoryId)->get();
    }
}
