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
    
    /**
     * Get css result count
     * @param type $cssId
     * @return count css result
     */
    public function getCountCssResultByCss($cssId){
        return self::where("css_id",$cssId)->count();
    }
    
    /**
     * When Css only have once Css result then use this to get Css result
     * @param int $cssId
     */
    public function getCssResultFirstByCss($cssId){
        return self::where('css_id', $cssId)->first();
    }
    
    /**
     * Get Css result list by Css
     * @param int $cssId
     * @param int $perPage
     * @return object list css result
     */
    public function getCssResulByCss($cssId, $perPage){
        return self::where("css_id",$cssId)
                ->orderBy('id', 'desc')
                ->paginate($perPage);
    }
}
