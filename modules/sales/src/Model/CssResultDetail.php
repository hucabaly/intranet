<?php

namespace Rikkei\Sales\Model;

use Illuminate\Database\Eloquent\Model;

class CssResultDetail extends Model
{
    protected $table = 'css_result_detail';
    
    public $timestamps = false;
    
    /**
     * Insert into table css_result_detail
     * @param array $data
     */
    public function insertCssResultDetail($cssResultId, $arrQuestion){
        try {
            if (count($arrQuestion)) {
                $countQuestion = count($arrQuestion); 
                for($i=0; $i<$countQuestion; $i++){
                    $detail = new CssResultDetail();
                    $detail->css_result_id = $cssResultId;
                    $detail->question_id = $arrQuestion[$i][0];
                    $detail->point = $arrQuestion[$i][1];
                    $detail->comment = $arrQuestion[$i][2];
                    
                    $detail->save();
                 }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
    
    /**
     * Get css result detail by css result
     * @param int $cssResultId
     */
    public function getResultDetailByCssResult($cssResultId){
        return self::where('css_result_id', $cssResultId)->get();
    }
    
    /**
     * Get a row of result detail
     * @param int $resultId
     * @param int $questionId
     */
    public function getResultDetailRow($resultId, $questionId){
        return self::where(['css_result_id' => $resultId, 'question_id' => $questionId])->first();
    }
}
