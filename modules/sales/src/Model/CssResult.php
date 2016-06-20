<?php

namespace Rikkei\Sales\Model;

use Illuminate\Database\Eloquent\Model;

class CssResult extends Model
{
    protected $table = 'css_result';
    
    /**
     * Insert into table css_result
     * @param array $data
     */
    public function insertCssResult($data){
        try {
            $cssResult = new CssResult();
            $cssResult->css_id = $data["css_id"];
            $cssResult->name = $data["name"];
            $cssResult->email = $data["email"];
            $cssResult->comment_overview = $data["comment_overview"];
            $cssResult->proposed = $data["proposed"];
            $cssResult->avg_point = $data["avg_point"];
            $cssResult->save();        
            
            return $cssResult->id;
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
}
