<?php

namespace Rikkei\Team\View;

use Rikkei\Team\Model\Team;

class TeamList
{
    /**
     * Team List tree
     * 
     * @return type
     */
    public static function getTreeHtml()
    {
        $html = '<ul class="treeview team-tree">';
        $html .= self::getTreeDataRecursive(0,0);
        $html .= '</ul>';
        return $html;
    }
    
    /**
     * get team tree option recursive
     * 
     * @param int $id
     * @param int $level
     */
    protected static function getTreeDataRecursive($parentId = 0, $level = 0) {
        $teamList = Team::select('id', 'name', 'parent_id')
            ->where('parent_id', $parentId)
            ->orderBy('position', 'asc')
            ->get();
        $countCollection = count($teamList);
        if(!$countCollection) {
            return;
        }
        $html = '';
        $i = 0;
        foreach($teamList as $team) {
            $classLi = '';
            $classLabel = 'team-item';
            $optionA = " data-id=\"{$team->id}\"";
            if($i == $countCollection - 1) {
                $classLi = 'last';
            }
            $classLi = $classLi ? " class=\"{$classLi}\"" : '';
            $classLabel = $classLabel ? " class=\"{$classLabel}\"" : '';
            $hrefA = app('request')->fullUrlWithQuery([
                'team' => $team->id
            ]);
            
            $html .= "<li{$classLi}>";
            $html .= "<label{$classLabel}>";
            $html .= "<a href=\"{$hrefA}\"{$optionA}>";
            $html .= $team->name;
            $html .= '</a>';
            $html .= '</label>';
            $htmlChild = self::getTreeDataRecursive($team->id, $level+1);
            if($html) {
                $html .= '<ul>';
                $html .= $htmlChild;
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
    
    public static function toOption($skipId = null, $isFunction = false)
    {
        $options = [];
        self::toOptionFunctionRecursive($options, 0,  $skipId, $isFunction, $level = 0);
        return $options;
    }
    
    protected static function toOptionFunctionRecursive(&$options, $parentId, $skipId, $isFunction, $level)
    {
        $teamList = Team::select('id', 'name', 'parent_id')
            ->where('parent_id', $parentId)
            ->orderBy('position', 'asc');
        if ($isFunction) {
            $teamList = $teamList->where('is_function', 1)
                ->where('permission_as', '0');
        }
        if ($skipId) {
            $teamList = $teamList->where('id', '<>', $skipId);
        }
        $teamList = $teamList->get();
        $countCollection = count($teamList);
        if(!$countCollection) {
            return;
        }
        $prefixLabel = '';
        for($i = 0 ; $i < $level ; $i++) {
            $prefixLabel .= '----&nbsp;&nbsp;';
        }
        foreach($teamList as $team) {
            $options[] = [
                'label' => $prefixLabel . $team->name,
                'value' => $team->id,
            ];
            self::toOptionFunctionRecursive($options, $team->id,  $skipId, $isFunction, $level + 1);
        }
    }
}
