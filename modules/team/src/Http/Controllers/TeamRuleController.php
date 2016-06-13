<?php

namespace Rikkei\Team\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Rikkei\Team\Model\Team;
use Lang;
use Rikkei\Team\Model\Permissions;
use Url;

class TeamRuleController extends TeamBaseController
{    
    /**
     * save team rule
     */
    public function save()
    {
        $teamId = Input::get('team.id');
        if (! $teamId) {
            return redirect()->route('team::setting.team.index')->withErrors(Lang::get('team::messages.Not found team.'));
        }
        $team = Team::find($teamId);
        if (! $team) {
            return redirect()->route('team::setting.team.index')->withErrors(Lang::get('team::messages.Not found team.'));
        }
        if (! $team->is_function) {
            return redirect()->route('team::setting.team.view', ['id' => $teamId])
                ->withErrors(Lang::get('team::view.Team is not function'));
        }
        if ($teamAs = $team->getTeamPermissionAs()) {
            $message = Lang::get('team::view.Team permisstion as team') .' ';
            $message .= '<a href="' . Url::route('team::setting.team.view', ['id' => $teamAs->id]) . '">';
            $message .= $teamAs->name;
            $message .= '</a>';
            return redirect()->route('team::setting.team.view', ['id' => $teamId])
                ->withErrors($message);
        }
        $permissions = Input::get('permission');
        try {
            Permissions::saveRule($permissions, $teamId);
            return redirect()->route('team::setting.team.view', ['id' => $teamId])->with('messages', [
                    'success' => [
                        Lang::get('team::messages.Save data success!')
                    ]
                ]);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.team.view', ['id' => $teamId])->withErrors($ex);
        }
    }
}
