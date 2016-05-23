<?php

namespace Rikkei\Team\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Rikkei\Team\Model\Team;
use Lang;
use Validator;
use Rikkei\Core\View\Form;

class TeamController extends TeamBaseController
{
    /**
     * view team
     * 
     * @param int $id
     */
    public function view($id)
    {
        $model = Team::find($id);
        if(!$model) {
            return redirect()->route('team::setting.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        Form::setData($model);
        return view('team::setting.index');
    }
    
    /**
     * save team
     */
    public function save()
    {
        if($id = Input::get('item.id')) {
            $model = Team::find($id);
        } else {
            $model = new Team();
        }
        $dataItem = Input::get('item');
        if(!Input::get('item.is_function')) {
            $dataItem['is_function'] = 0;
            $dataItem['permission_as'] = 0;
        } elseif (!Input::get('permission_same')) {
            $dataItem['permission_as'] = 0;
        }
        $validator = Validator::make($dataItem, [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            Form::setData($dataItem);
            Form::setData();
            if($model->id) {
                return redirect()->route('team::setting.team.view', [
                    'id' => $model->id
                ])->withErrors($validator);
            }
            return redirect()->route('team::setting.index')
                ->withErrors($validator);
        }
        //calculate position
        if(!$model->id) { //team new
            $parentId = 0;
            if($dataItem['parent_id']) {
                $parentId = $dataItem['parent_id'];
            }
            $teamSameParent = Team::select('id', 'position')
                ->where('parent_id', $parentId)
                ->orderBy('position', 'desc')
                ->first();
            if (count($teamSameParent)) {
                $dataItem['position'] = $teamSameParent ->position + 1;
            } else {
                $dataItem['position'] = 0;
            }
        }
        
        try {
            $model->setData($dataItem);
            $result = $model->save();
            if(!$result) {
                return redirect()->route('team::setting.index')
                    ->withErrors(Lang::get('team::messages.Error save date, please try again!'));
            }
            return redirect()->route('team::setting.team.view', [
                'id' => $model->id
            ])->with('messages', [
                    'success' => [
                        Lang::get('team::messages.Save data success!')
                    ]
                ]);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.index')->withErrors($ex);
        }
    }
    
    /**
     * move team
     */
    public function move()
    {
        $id = Input::get('id');
        if (!$id) {
            return redirect()->route('team::setting.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        $model = Team::find($id);
        if(!$model) {
            return redirect()->route('team::setting.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        try {
            if(Input::get('move_up')) {
                $model->move(true);
            } else {
                $model->move(false);
            }
            
             return redirect()->route('team::setting.team.view', [
                'id' => $id
                ])->with('messages', [
                        'success' => [
                            Lang::get('team::messages.Move item success!')
                        ]
                    ]);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.team.view', [
                    'id' => $id
                ])->withErrors($ex);
        }
    }
}
