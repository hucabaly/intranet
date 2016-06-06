<?php

namespace Rikkei\Team\Http\Controllers;

use Rikkei\Core\View\Breadcrumb;
use URL;
use Rikkei\Team\Model\Employees;
use Rikkei\Core\View\Form;

class MemberController extends TeamBaseController
{
    /**
     * construct more
     */
    protected function _construct()
    {
        Breadcrumb::add('Team');
        Breadcrumb::add('Member', URL::route('team::team.member.index'));
    }
    
    /**
     * list member
     */
    public function index()
    {
        return view('team::member.index', [
            'collectionModel' => Employees::getGridData()
        ]);
    }
    
    /**
     * create member
     */
    public function create()
    {
        echo 'create';
    }
    /**
     * view/edit member
     * 
     * @param int $id
     */
    public function edit($id)
    {
        $model = Employees::find($id);
        if (! $model) {
            return redirect()->route('team::team.member.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        Breadcrumb::add($model->name, URL::route('team::team.member.edit', ['id' => $id]));
        Form::setData($model);
        return view('team::member.edit', [
            
        ]);
    }
    
    /**
     * save member
     */
    public function save()
    {
        echo 'save';
    }
    
    /**
     * delete member
     */
    public function delete()
    {
        echo 'delete';
    }
}

