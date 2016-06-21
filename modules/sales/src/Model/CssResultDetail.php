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
}
