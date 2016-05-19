<?php

namespace Rikkei\Team\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Rikkei\Team\Model\Team;
use Lang;
use Validator;
use Rikkei\Core\View\Form;

class SettingController extends AuthBaseController
{
    public function index()
    {
        return view('team::setting.index');
    }
    
    public function saveTeam()
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
        } elseif (Input::get('permission_new')) {
            $dataItem['permission_as'] = 0;
        }
        $validator = Validator::make($dataItem, [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            Form::setData($dataItem);
            Form::setData();
            return redirect()->route('team::setting.index')
                ->withErrors($validator);
        }
        try {
            $model->setData($dataItem);
            $result = $model->save();
            if(!$result) {
                return redirect()->route('team::setting.index')->withErrors(Lang::get('team::messages.Error save date, please try again!'));
            }
            return redirect()->route('team::setting.index')->with('messages', [
                    'success' => [
                        Lang::get('team::messages.Save data success!')
                    ]
                ]);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.index')->withErrors($ex);
        }
        
        
    }
}
