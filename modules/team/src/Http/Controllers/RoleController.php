<?php

namespace Rikkei\Team\Http\Controllers;

use Rikkei\Core\View\Breadcrumb;
use URL;
use Rikkei\Team\Model\Roles;
use Rikkei\Core\View\Form;
use Lang;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Rikkei\Team\Model\EmployeeRole;
use Rikkei\Team\Model\RoleRule;

class RoleController extends TeamBaseController
{
    /**
     * construct more
     */
    protected function _construct()
    {
        Breadcrumb::add('Setting');
        Breadcrumb::add('Role', URL::route('team::setting.role.index'));
    }
    
    /**
     * list role
     */
    public function index()
    {
        return view('team::role.index', [
            'collectionModel' => Roles::getGridData()
        ]);
    }
    
    /**
     * create role
     */
    public function create()
    {
        return view('team::role.edit', [
            'roleRule' => []
        ]);
    }
    /**
     * view/edit role
     * 
     * @param int $id
     */
    public function edit($id)
    {
        $model = Roles::find($id);
        if (! $model) {
            return redirect()->route('team::setting.role.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        Breadcrumb::add($model->name, URL::route('team::setting.role.edit', ['id' => $id]));
        Form::setData($model);
        return view('team::role.edit', [
            'roleRule' => RoleRule::where('role_id', $id)->get(),
            'collectionModel' => EmployeeRole::getGridData($id)
        ]);
    }
    
    /**
     * save role
     */
    public function save()
    {
        // if submit is delete
        if (Input::get('delete')) {
            return $this->delete();
        }
        // if submit is save
        $id = Input::get('id');
        $dataItem = Input::get('item');
        if ($id) {
            $model = Roles::find($id);
            if (! $model) {
                return redirect()->route('team::setting.role.index')
                    ->withErrors(Lang::get('team::messages.Not found item.'));
            }
        } else {
            $model = new Roles();
        }
        
        $validator = Validator::make($dataItem, [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            Form::setData($dataItem);
            if ($model->id) {
                return redirect()->route('team::setting.role.edit', [
                        'id' => $model->id
                    ])->withErrors($validator);
            }
            return redirect()->route('team::setting.role.index')->withErrors($validator);
        }
        $model->setData($dataItem);
        try {
            $result = $model->save((array)Input::get('rule'));
            if (!$result) {
                return redirect()->route('team::setting.role.index')
                    ->withErrors(Lang::get('team::messages.Error save data, please try again!'));
            }
            $messages = [
                    'success'=> [
                        Lang::get('team::messages.Save data success!'),
                    ]
            ];
            if (Input::get('submit_continue')) {
                return redirect()->route('team::setting.role.edit', [
                    'id' => $model->id
                ])->with('messages', $messages);
            }
            return redirect()->route('team::setting.role.index')->with('messages', $messages);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.role.index')->withErrors($ex);
        }
    }
    
    /**
     * delete role
     */
    public function delete()
    {
        $id = Input::get('id');
        $model = Roles::find($id);
        if (! $model) {
            return redirect()->route('team::setting.role.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        try {
            $model->delete();
            $messages = [
                    'success'=> [
                        Lang::get('team::messages.Delete item success!'),
                    ]
            ];
            return redirect()->route('team::setting.role.index')->with('messages', $messages);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.role.index')->withErrors($ex);
        }
    }
}

