<?php

namespace Rikkei\Team\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Rikkei\Team\Model\Team;
use Lang;
use Rikkei\Team\Model\TeamRule;

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
        $rules = Input::get('rule');
        try {
            TeamRule::saveRule($rules, $teamId);
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
