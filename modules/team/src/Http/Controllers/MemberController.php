<?php

namespace Rikkei\Team\Http\Controllers;

use Rikkei\Core\View\Breadcrumb;
use URL;
use Rikkei\Team\Model\Employees;
use Rikkei\Core\View\Form;
use Illuminate\Support\Facades\Input;
use Lang;
use Rikkei\Core\View\Menu;
use Illuminate\Support\Facades\Validator;
use Rikkei\Core\View\View;
use Rikkei\Recruitment\Model\RecruitmentApplies;
use Rikkei\Team\View\Permission;
use Rikkei\Team\Model\Roles;

class MemberController extends \Rikkei\Core\Http\Controllers\Controller
{
    /**
     * construct more
     */
    protected function _construct()
    {
        Breadcrumb::add('Team');
        Breadcrumb::add('Member', URL::route('team::team.member.index'));
        Menu::setActive('team');
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
        $presenter = null;
        if ($model->recruitment_apply_id) {
            $presenter = RecruitmentApplies::getPresenterName($model->recruitment_apply_id, false);
            if ($presenter) {
                Form::setData([
                    'recruitment.present' => $presenter
                ]);
            }
        }
        
        Form::setData($model, 'employee');
        return view('team::member.edit', [
            'employeeTeamPositions' => $model->getTeamPositons(),
            'employeeRoles' => $model->getRoles(),
            'recruitmentPresent' => $presenter
        ]);
    }
    
    /**
     * save member
     */
    public function save()
    {
        $id = Input::get('id');
        if ($id) {
            $model = Employees::find($id);
            if (! $model) {
                return redirect()->route('team::team.member.index')->withErrors(Lang::get('team::messages.Not found item.'));
            }
            
            //TODO permission check greater
            
        } else {
            //check permission creation
            if (! Permission::getInstance()->isAllow('team::team.member.create')) {
                View::viewErrorPermission();
            }
            $model = new Employees();
        }
        $dataEmployee = Input::get('employee');
        $teamPostions = (array) Input::get('team');
        if (isset($dataEmployee['employee_code'])) {
            unset($dataEmployee['employee_code']);
        }
        if (isset($teamPostions[0])) {
            unset($teamPostions[0]);
        }
        
        if (! $id) {
            Form::setData($dataEmployee, 'employee');
            Form::setData($teamPostions, 'employee_team');
            $roles = (array) Input::get('role');
            $employeeRole = Roles::select('id as role_id', 'role')
                ->whereIn('id', $roles)
                ->where('special_flg', Roles::FLAG_ROLE)
                ->orderBy('role')
                ->get();
            if (count($employeeRole)) {
                Form::setData($employeeRole, 'employee_role');
            }
        }
        $validator = Validator::make($dataEmployee, [
            'employee_card_id' => 'required|integer',
            'name' => 'required|max:255',
            'id_card_number' => 'required|max:255',
            'email' => 'required|max:255|email|unique:employees,email,' . $id,
            'join_date' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            if ($id) {
                return redirect()->route('team::team.member.edit', ['id' => $id])->withErrors($validator);
            }
            return redirect()->route('team::team.member.create')->withErrors($validator);
        }
        //check email of rikkei
        if (! View::isEmailAllow($dataEmployee['email'])) {
            $message = Lang::get('team::messages.Please enter email of Rikkeisoft');
            if ($id) {
                return redirect()->route('team::team.member.edit', ['id' => $id])->withErrors($message);
            }
            return redirect()->route('team::team.member.create')->withErrors($message);
        }
        
        //check employee_card_id same
        $employeeSameCard = Employees::select('id')
            ->where('id', '<>', $id)
            ->where('employee_card_id', $dataEmployee['employee_card_id'])
            ->where('leave_date', null)
            ->first();
        if ($employeeSameCard) {
            if ($id) {
                return redirect()->route('team::team.member.edit', ['id' => $id])
                    ->withErrors(Lang::get('team::messages.Coinciding employee card code'));
            }
            return redirect()->route('team::team.member.create')
                ->withErrors(Lang::get('team::messages.Coinciding employee card code'));
        }
        
        //process team
        if (! $teamPostions || ! count($teamPostions)) {
            if ($id) {
                return redirect()->route('team::team.member.edit', ['id' => $id])
                        ->withErrors(Lang::get('team::view.Employees must belong to at least one team'));
            }
            return redirect()->route('team::team.member.create')
                ->withErrors(Lang::get('team::view.Employees must belong to at least one team'));
        }
        
        //save model
        $model->setData($dataEmployee);
        $model->save();
        
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
        Menu::setActive('hr', '/');
        Breadcrumb::add(Lang::get('team::view.Create new'), URL::route('team::team.member.create'));
        return view('team::member.edit');
    }
}

