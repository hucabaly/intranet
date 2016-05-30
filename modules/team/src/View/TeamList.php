<?php

namespace Rikkei\Team\View;

use Rikkei\Team\Model\Team;
use Lang;

class TeamList
{
    /**
     * Team List tree
     * 
     * @return type
     */
    public static function getTreeHtml($idActive = null)
    {
        $html = '<ul class="treeview team-tree">';
        $html .= self::getTreeDataRecursive(0, 0, $idActive);
        $html .= '</ul>';
        return $html;
    }
    
    /**
     * get team tree option recursive
     * 
     * @param int $id
     * @param int $level
     */
    protected static function getTreeDataRecursive($parentId = 0, $level = 0, $idActive = null) 
    {
        $teamList = Team::select('id', 'name', 'parent_id')
                ->where('parent_id', $parentId)
                ->orderBy('position', 'asc')
                ->get();
        $countCollection = count($teamList);
        if (!$countCollection) {
            return;
        }
        $html = '';
        $i = 0;
        foreach ($teamList as $team) {
            $classLi = '';
            $classLabel = 'team-item';
            $optionA = " data-id=\"{$team->id}\"";
            $classA = '';
            if ($i == $countCollection - 1) {
                $classLi = 'last';
            }
            if ($team->id == $idActive) {
                $classA .= 'active';
            }
            $classLi = $classLi ? " class=\"{$classLi}\"" : '';
            $classLabel = $classLabel ? " class=\"{$classLabel}\"" : '';
            $classA = $classA ? " class=\"{$classA}\"" : '';

            $hrefA = route('team::setting.team.view', ['id' => $team->id]);
            $html .= "<li{$classLi}>";
            $html .= "<label{$classLabel}>";
            $html .= "<a href=\"{$hrefA}\"{$classA}{$optionA} level='$level'>";
            $html .= $team->name;
            $html .= '</a>';
            $html .= '</label>';
            $htmlChild = self::getTreeDataRecursive($team->id, $level + 1, $idActive);
            if ($html) {
                $html .= '<ul>';
                $html .= $htmlChild;
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
    
    /**
     * Team List to option
     * 
     * @param int|null $skipId
     * @param boolean $isFunction
     * @param boolean $valueNull
     * @return array
     */
    public static function toOption($skipId = null, $isFunction = false, $valueNull = true)
    {
        $options = [];
        if ($valueNull) {
            $options[] = [
                'label' => Lang::get('team::view.--Please choose--'),
                'value' => '',
                'option' => '',
            ];
        }
        self::toOptionFunctionRecursive($options, 0, $skipId, $isFunction, $level = 0);
        return $options;
    }
    
    /**
     * Team list to option recuresive call all child
     * 
     * @param array $options
     * @param int $parentId
     * @param int|null $skipId
     * @param boolean $isFunction
     * @param int $level
     */
    protected static function toOptionFunctionRecursive(&$options, $parentId, $skipId, $isFunction, $level)
    {
        $teamList = Team::select('id', 'name', 'parent_id', 'is_function', 'permission_as')
                ->where('parent_id', $parentId)
                ->orderBy('position', 'asc');
        if ($skipId) {
            $teamList = $teamList->where('id', '<>', $skipId);
        }
        $teamList = $teamList->get();
        $countCollection = count($teamList);
        if (!$countCollection) {
            return;
        }
        $prefixLabel = '';
        for ($i = 0; $i < $level; $i++) {
            $prefixLabel .= '----&nbsp;&nbsp;';
        }
        foreach ($teamList as $team) {
            if ($isFunction && (!$team->is_function || $team->permission_as)) {
                $optionMore = ' disabled';
            } else {
                $optionMore = '';
            }
            $options[] = [
                'label' => $prefixLabel . $team->name,
                'value' => $team->id,
                'option' => $optionMore
            ];
            self::toOptionFunctionRecursive($options, $team->id, $skipId, $isFunction, $level + 1);
        }
    }
}
