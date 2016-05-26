<?php

namespace Rikkei\Team\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Rikkei\Team\Model\Team;
use Lang;
use Validator;
use Rikkei\Core\View\Form;
use Rikkei\Core\View\Breadcrumb;
use URL;
use Rikkei\Team\Model\Position;

class PositionController extends TeamBaseController
{
    /**
     * construct more
     */
    protected function _construct()
    {
        Breadcrumb::add('Setting');
        Breadcrumb::add('Team', URL::route('team::setting.team.index'));
    }

    /**
     * view team position
     * 
     * @param int $id
     */
    public function view($id)
    {
        $model = Position::find($id);
        if (! $model) {
            return redirect()->route('team::setting.team.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        Form::setData($model, 'position');
        return view('team::setting.index');
    }
    
    /**
     * save team positon
     */
    public function save()
    {
        $id = Input::get('position.id');
        $dataItem = Input::get('position');
        if ($id) {
            $model = Position::find($id);
            if(!count($model)) {
                return redirect()->route('team::setting.team.index')
                    ->withErrors(Lang::get('team::messages.Please choose team to do this action'));
            }
        } else {
            $model = new Position();
        }
        
        $validator = Validator::make($dataItem, [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            Form::setData($dataItem);
            Form::setData();
            if ($model->id) {
                return redirect()->route('team::setting.team.position.view', [
                        'id' => $model->id
                    ])->withErrors($validator);
            }
            return redirect()->route('team::setting.team.index')->withErrors($validator);
        }
        
        //calculate position level
        if (! $id) { //position new
            $positionLast = Position::select('level')
                ->orderBy('level', 'desc')
                ->first();
            if (count($positionLast)) {
                $dataItem['level'] = $positionLast->level + 1;
            } else {
                $dataItem['level'] = 1;
            }
        }
        
        try {
            $model->setData($dataItem);
            $result = $model->save();
            if (!$result) {
                return redirect()->route('team::setting.team.index')
                    ->withErrors(Lang::get('team::messages.Error save data, please try again!'));
            }
            return redirect()->route('team::setting.team.position.view', [
                    'id' => $model->id
                ])->with('messages', [
                    'success' => [
                        Lang::get('team::messages.Save data success!')
                    ]
                ]);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.team.index')
                    ->withErrors($ex);
        }
    }
    
    /**
     * Delete team position
     * @return type
     */
    public function delete()
    {
        $id = Input::get('id');
        if (!$id) {
            return redirect()->route('team::setting.team.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        $model = Position::find($id);
        if (!$model) {
            return redirect()->route('team::setting.team.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        try {
            $model->delete();
            return redirect()->route('team::setting.team.index')
                ->with('messages', [
                    'success' => [
                        Lang::get('team::messages.Delete item success!')
                    ]
                ]);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.team.position.view', [
                    'id' => $id
                ])->withErrors($ex);
        }
    }
    
    /**
     * move team
     */
    public function move()
    {
        $id = Input::get('id');
        if (!$id) {
            return redirect()->route('team::setting.team.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        $model = Position::find($id);
        if (!$model) {
            return redirect()->route('team::setting.team.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        try {
            if (Input::get('move_up')) {
                $model->move(true);
            } else {
                $model->move(false);
            }

            return redirect()->route('team::setting.team.position.view', [
                    'id' => $id
                ])->with('messages', [
                    'success' => [
                        Lang::get('team::messages.Move item success!')
                    ]
                ]);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.team.position.view', [
                    'id' => $id
                ])->withErrors($ex);
        }
    }
}
