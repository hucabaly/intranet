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
use Rikkei\Team\Model\Team;

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
    public function index($id = null)
    {
        $teamIdsAvailable = null;
        //scope company => view all team
        if (Permission::getInstance()->isScopeCompany()) {
            $teamIdsAvailable = true;
        } elseif (Permission::getInstance()->isScopeTeam()){
            //scope team => check
            $employeeCurrent = Permission::getInstance()->getEmployee();
            $teamIdsAvailable = (array) $employeeCurrent->getTeamIdIsLeader();
            //employee not is leader
            if (! $teamIdsAvailable) {
                View::viewErrorPermission();
            }
            //check scope comany of each team
            foreach ($teamIdsAvailable as $key => $teamId) {
                if (! Permission::getInstance()->isScopeTeam($teamId)) {
                    unset($teamIdsAvailable[$key]);
                }
            }
            if (! $teamIdsAvailable) {
                View::viewErrorPermission();
            }
            if (! $id) {
                $id = reset($teamIdsAvailable);    
            }
            if (! in_array($id, $teamIdsAvailable)) {
                View::viewErrorPermission();
            }
        } else {
            View::viewErrorPermission();
        }
        return view('team::member.index', [
            'collectionModel' => Team::getMemberGridData($id),
            'teamIdCurrent' => $id,
            'teamIdsAvailable' => $teamIdsAvailable
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
        $employeeScopeCompany = Permission::getInstance()->isScopeCompany();
        $employeeGreater = Permission::getInstance()->getEmployee()->isGreater($model);
        if (! $employeeScopeCompany && ! $employeeGreater) {
            View::viewErrorPermission();
        }
        // get permssion company and leader greater
        $employeeGreaterLeader = Permission::getInstance()->getEmployee()->isGreater($model, true);
        if (! $employeeScopeCompany && ! $employeeGreaterLeader) {
            $employeePermission = false;
        } else {
            $employeePermission = true;
        }
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
            'recruitmentPresent' => $presenter,
            'employeeGreaterLeader' => $employeeGreaterLeader,
            'employeeModelItem' => $model,
        ]);
    }
    
    /**
     * save member
     */
    public function save()
    {
        Menu::removeActive();
        if (Input::get('is_profile')) {
            $model = Permission::getInstance()->getEmployee();
            $id = $model->id;
            $employeeGreater = $model->isLeader();
        } else {
            $id = Input::get('id');
            if ($id) {
                $model = Employees::find($id);
                if (! $model) {
                    if (Input::get('is_profile')) {
                        return redirect()->route('team::member.profile')->withErrors(Lang::get('team::messages.Not found item.'));
                    }
                    return redirect()->route('team::team.member.index')->withErrors(Lang::get('team::messages.Not found item.'));
                }
                //check permission save edit
                $employeeScopeCompany = 
                    Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit') ||
                    Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit.team.position') ||
                    Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit.role');
                $employeeGreater = Permission::getInstance()->getEmployee()->isGreater($model, true);
                if (! $employeeScopeCompany && ! $employeeGreater) {
                    View::viewErrorPermission();
                }            
            } else {
                //check permission creation
                if (! Permission::getInstance()->isAllow('team::team.member.create')) {
                    View::viewErrorPermission();
                }
                $model = new Employees();
            }
        }
        $dataEmployee = (array) Input::get('employee');
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
            Form::setData(['data' => Input::get('employee_skill')], 
                'employee_skill');
            Form::setData(['data' => Input::get('employee_skill_change')], 
                'employee_skill_change');
            
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
        if ($dataEmployee) {
            $validator = Validator::make($dataEmployee, [
                'employee_card_id' => 'required|integer',
                'name' => 'required|max:255',
                'id_card_number' => 'required|max:255',
                'email' => 'required|max:255|email|unique:employees,email,' . $id,
                'join_date' => 'required|max:255',
            ]);
            if ($validator->fails()) {
                if (Input::get('is_profile')) {
                    return redirect()->route('team::member.profile')->withErrors($validator);
                }
                if ($id) {
                    return redirect()->route('team::team.member.edit', ['id' => $id])->withErrors($validator);
                }
                return redirect()->route('team::team.member.create')->withErrors($validator);
            }
        
            //check email of rikkei
            if (! View::isEmailAllow($dataEmployee['email'])) {
                $message = Lang::get('team::messages.Please enter email of Rikkeisoft');
                if (Input::get('is_profile')) {
                    return redirect()->route('team::member.profile')->withErrors($message);
                }
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
                if (Input::get('is_profile')) {
                    return redirect()->route('team::member.profile')
                        ->withErrors(Lang::get('team::messages.Coinciding employee card code'));
                }
                if ($id) {
                    return redirect()->route('team::team.member.edit', ['id' => $id])
                        ->withErrors(Lang::get('team::messages.Coinciding employee card code'));
                }
                return redirect()->route('team::team.member.create')
                    ->withErrors(Lang::get('team::messages.Coinciding employee card code'));
            }
        }
        
        //process team
        $employeePermissionTeam = Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit.team.position');
        
        if ($employeePermissionTeam || $employeeGreater) {
            if (! $teamPostions || ! count($teamPostions)) {
                if (Input::get('is_profile')) {
                    return redirect()->route('team::member.profile')
                        ->withErrors(Lang::get('team::view.Employees must belong to at least one team'));
                }
                if ($id) {
                    return redirect()->route('team::team.member.edit', ['id' => $id])
                            ->withErrors(Lang::get('team::view.Employees must belong to at least one team'));
                }
                return redirect()->route('team::team.member.create')
                    ->withErrors(Lang::get('team::view.Employees must belong to at least one team'));
            }
        }
        
        //save model
        //checkpermission
        if ($id) {
            $allInput = Input::all();
            // not allow update information
            if (! Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit')) {
                $dataEmployee = [];
            }
            //not allow update team
            if (! Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit.team.position') && 
                ! $employeeGreater) {
                unset($allInput['team']);
                Input::replace($allInput);
            }
            //not allow update role
            if (! Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit.role') && 
                ! $employeeGreater) {
                unset($allInput['role']);
                Input::replace($allInput);
            }
        }
        $model->setData($dataEmployee);
        $model->save();
        
        $messages = [
                'success'=> [
                    Lang::get('team::messages.Save data success!'),
                ]
        ];
        if (Input::get('is_profile')) {
            return redirect()->route('team::member.profile')
                ->with('messages', $messages);
        }
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
        if (! Permission::getInstance()->isScopeCompany()) {
            Menu::removeActive();
            View::viewErrorPermission();
        }
        Menu::setActive('hr', '/');
        Breadcrumb::add(Lang::get('team::view.Create new'), URL::route('team::team.member.create'));
        return view('team::member.edit', [
            'employeeGreaterLeader' => true
        ]);
    }
}

