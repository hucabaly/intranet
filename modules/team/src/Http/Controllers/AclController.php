<?php

namespace Rikkei\Team\Http\Controllers;

use Rikkei\Core\View\Breadcrumb;
use URL;
use Rikkei\Core\View\Form;
use Illuminate\Support\Facades\Input;
use Lang;
use Rikkei\Core\View\Menu;
use Rikkei\Team\Model\Action;
use Rikkei\Team\View\Translate;

class AclController extends \Rikkei\Core\Http\Controllers\Controller
{
    /**
     * construct more
     */
    protected function _construct()
    {
        Breadcrumb::add('Acl', URL::route('team::setting.acl.index'));
        Menu::setActive('setting');
    }
    
    /**
     * list acl
     */
    public function index()
    {
        return view('team::acl.index', [
            'collectionModel' => Action::getGridData()
        ]);
    }
    
    /**
     * view/edit acl
     * 
     * @param int $id
     */
    public function edit($id)
    {
        $model = Action::find($id);
        if (! $model) {
            return redirect()->route('team::setting.acl.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        Form::setData($model, 'acl');
        return view('team::acl.edit');
    }
    
    /**
     * save acl
     */
    public function save()
    {
        if (Input::get('submit_delete')) {
            return $this->delete();
        }
        $id = Input::get('id');
        if (! $id) {
            $model = new Action();
        } else {
            $model = Action::find($id);
            if (! $model) {
                return redirect()->route('team::setting.acl.index')->withErrors(Lang::get('team::messages.Not found item.'));
            }
        }
        $itemData = (array) Input::get('item');
        if (isset($itemData['parent_id']) && ! $itemData['parent_id']) {
            $itemData['parent_id'] = null;
        }
        $model->setData($itemData);
        $model->save();
        Translate::writeWord($model->description, Input::get('trans.description'), 'acl');
        $messages = [
                'success'=> [
                    Lang::get('team::messages.Save data success!'),
                ]
        ];
        return redirect()->route('team::setting.acl.edit', ['id' => $model->id])->with('messages', $messages);
    }
    
    /**
     * create acl
     */
    public function create()
    {
        return view('team::acl.edit');
    }
    
    /**
     * delete acl item
     */
    public function delete()
    {
        $id = Input::get('id');
        $model = Action::find($id);
        if (! $model) {
            return redirect()->route('team::setting.acl.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        $model->delete();
        $messages = [
                'success'=> [
                    Lang::get('team::messages.Delete item success!'),
                ]
        ];
        return redirect()->route('team::setting.acl.index')->with('messages', $messages);
    }
}
