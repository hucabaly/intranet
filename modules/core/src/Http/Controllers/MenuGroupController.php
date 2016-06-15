<?php

namespace Rikkei\Core\Http\Controllers;

use Rikkei\Core\View\Breadcrumb;
use URL;
use Rikkei\Core\View\Form;
use Illuminate\Support\Facades\Input;
use Lang;
use Rikkei\Core\View\Menu;
use Rikkei\Core\Model\Menus;
use Illuminate\Support\Facades\Validator;
use Exception;

class MenuGroupController extends Controller
{
    /**
     * construct more
     */
    protected function _construct()
    {
        Breadcrumb::add('Menu', URL::route('core::setting.menu.group.index'));
        Menu::setActive('setting');
    }
    
    /**
     * list menu group
     */
    public function index()
    {
        return view('core::menu.group.index', [
            'collectionModel' => Menus::getGridData()
        ]);
    }
    
    /**
     * view/edit menu group
     * 
     * @param int $id
     */
    public function edit($id)
    {
        $model = Menus::find($id);
        if (! $model) {
            return redirect()->route('core::setting.menu.group.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        Form::setData($model, 'menus');
        return view('core::menu.group.edit');
    }
    
    /**
     * save member
     */
    public function save()
    {
        $id = Input::get('id');
        if (! $id) {
            $model = new Menus();
        } else {
            $model = Menus::find($id);
            if (! $model) {
                return redirect()->route('core::setting.menu.group.menu')->withErrors(Lang::get('team::messages.Not found item.'));
            }
        }
        $dataItem = Input::get('item');
        $validator = Validator::make($dataItem, [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            if ($id) {
                return redirect()->route('core::setting.menu.group.edit', [
                        'id' => $id
                    ])->withErrors($validator);
            }
            return redirect()->route('core::setting.menu.group.index')->withErrors($validator);
        }
        $model->setData($dataItem);
        try {
            $model->save();
        } catch (Exception $ex) {
            return redirect()->route('core::setting.menu.group.index')->withErrors($ex);
        }
        $messages = [
                'success'=> [
                    Lang::get('team::messages.Save data success!'),
                ]
        ];
        return redirect()->route('core::setting.menu.group.edit', ['id' => $model->id])->with('messages', $messages);
    }
    
    /**
     * create member
     */
    public function create()
    {
        return view('core::menu.group.edit');
    }
}


