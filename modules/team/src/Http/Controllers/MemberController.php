<?php

namespace Rikkei\Team\Http\Controllers;

use Rikkei\Core\View\Breadcrumb;
use URL;
use Rikkei\Team\Model\Employees;
use Rikkei\Core\View\Form;
use Illuminate\Support\Facades\Input;
use Lang;

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
        
        //TODO permission check
        
        Breadcrumb::add($model->name, URL::route('team::team.member.edit', ['id' => $id]));
        Form::setData($model);
        return view('team::member.edit', [
            'employeeTeamPositions' => $model->getTeamPositons(),
            'employeeRoles' => $model->getRoles()
        ]);
    }
    
    /**
     * save member
     */
    public function save()
    {
        $id = Input::get('id');
        $model = Employees::find($id);
        if (! $model) {
            return redirect()->route('team::team.member.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        
        //TODO permission check
        
        $teamPostions = Input::get('team');
        $roles = Input::get('role');
        $teamPostions = (array) $teamPostions;
        $roles = (array) $roles;
        if (isset($teamPostions[0])) {
            unset($teamPostions[0]);
        }
        $model->saveTeamPosition($teamPostions);
        $model->saveRoles($roles);
        
        $messages = [
                'success'=> [
                    Lang::get('team::messages.Save data success!'),
                ]
        ];
        return redirect()->route('team::team.member.edit', ['id' => $model->id])->with('messages', $messages);
    }
    
    /**
     * delete member
     */
    public function leave()
    {
        echo 'leave';
    }
    
    /**
     * create member
     */
    public function create()
    {
        echo 'create';
    }
}

