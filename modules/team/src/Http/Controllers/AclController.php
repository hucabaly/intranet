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
use Exception;

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
     * list member
     */
    public function index()
    {
        return view('team::acl.index', [
            'collectionModel' => Action::getGridData()
        ]);
    }
    
    /**
     * view/edit member
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
     * save member
     */
    public function save()
    {
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
        try {
            $model->save();
        } catch (Exception $ex) {
            return redirect()->route('team:setting.acl')->withErrors($ex);
        }
        Translate::writeWord($model->description, Input::get('trans.description'), 'acl');
        $messages = [
                'success'=> [
                    Lang::get('team::messages.Save data success!'),
                ]
        ];
        return redirect()->route('team::setting.acl.edit', ['id' => $model->id])->with('messages', $messages);
    }
    
    /**
     * create member
     */
    public function create()
    {
        return view('team::acl.edit');
    }
}


