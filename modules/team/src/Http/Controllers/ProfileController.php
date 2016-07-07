<?php

namespace Rikkei\Team\Http\Controllers;

use Rikkei\Core\View\Breadcrumb;
use Rikkei\Core\View\Menu;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Rikkei\Team\View\Permission;
use Rikkei\Core\View\Form;

class ProfileController extends \Rikkei\Core\Http\Controllers\Controller
{
    /**
     * view/edit profile
     * 
     */
    public function profile()
    {
        Breadcrumb::add('Profile', URL::route('team::member.profile'));
        Menu::setActive('profile');
        
        $model = Permission::getInstance()->getEmployee();
        if (! $model) {
            return redirect()->route('/')->withErrors(Lang::get('team::messages.Not found item.'));
        }
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
            'employeeGreaterLeader' => $model->isLeader(),
            'isProfile' => true,
            'employeeModelItem' => $model,
        ]);
    }
}
