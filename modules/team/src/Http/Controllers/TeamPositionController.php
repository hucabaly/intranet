<?php

namespace Rikkei\Team\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Rikkei\Team\Model\Team;
use Lang;
use Validator;
use Rikkei\Core\View\Form;
use Rikkei\Core\View\Breadcrumb;
use URL;
use Rikkei\Team\Model\TeamPosition;

class TeamPositionController extends TeamBaseController
{
    /**
     * construct more
     */
    protected function _construct()
    {
        Breadcrumb::add('Setting');
        Breadcrumb::add('Team', URL::route('team::setting.index'));
    }

    /**
     * view team position
     * 
     * @param int $id
     */
    public function view($id)
    {
        $model = TeamPosition::find($id);
        if (! $model) {
            return redirect()->route('team::setting.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        $team = $model->getTeam();
        if (! $team) {
            return redirect()->route('team::setting.index')->withErrors(Lang::get('team::messages.Not found item.'));
        }
        Form::setData($team);
        Form::setData($model, 'position');
        return view('team::setting.index', [
            'teamPosition' => $team->getPosition()
        ]);
    }
    
    /**
     * save team positon
     */
    public function save()
    {
        $id = Input::get('position.id');
        $dataItem = Input::get('position');
        if ($id) {
            $model = TeamPosition::find($id);
            if(!count($model)) {
                return redirect()->route('team::setting.index')
                    ->withErrors(Lang::get('team::messages.Please choose team to do this action'));
            }
            $teamId = $model->team_id;
            $team = $model->getTeam();
            if (! count($team)) {
                return redirect()->route('team::setting.index')
                    ->withErrors(Lang::get('team::messages.Please choose team to do this action'));
            }
        } else {
            $teamId = Input::get('team.id');
            if (! $teamId) {
                return redirect()->route('team::setting.index')
                    ->withErrors(Lang::get('team::messages.Please choose team to do this action'));
            }
            $team = Team::find($teamId);
            if (! count($team)) {
                return redirect()->route('team::setting.index')
                    ->withErrors(Lang::get('team::messages.Please choose team to do this action'));
            }
            $dataItem['team_id'] = $teamId;
            $model = new TeamPosition();
        }
        
        $validator = Validator::make($dataItem, [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            Form::setData($dataItem);
            Form::setData();
            if ($model->id) {
                return redirect()->route('team::setting.team.position.view', [
                        'id' => $model->id
                    ])->withErrors($validator);
            }
            return redirect()->route('team::setting.team.view', [
                    'id' => $teamId
                ])->withErrors($validator);
        }
        //calculate position level
        if (! $id) { //team new
            $teamPositionLast = TeamPosition::select('level')
            ->where('team_id', $teamId)
            ->orderBy('level', 'desc')
            ->first();
            if (count($teamPositionLast)) {
                $dataItem['level'] = $teamPositionLast->level + 1;
            } else {
                $dataItem['level'] = 1;
            }
        }
        
        try {
            $model->setData($dataItem);
            $result = $model->save();
            if (!$result) {
                return redirect()->route('team::setting.team.view', [
                        'id' => $teamId
                    ])->withErrors(Lang::get('team::messages.Error save data, please try again!'));
            }
            return redirect()->route('team::setting.team.position.view', [
                    'id' => $model->id
                ])->with('messages', [
                    'success' => [
                        Lang::get('team::messages.Save data success!')
                    ]
                ]);
        } catch (Exception $ex) {
            return redirect()->route('team::setting.team.view', [
                    'id' => $teamId
                ])->withErrors($ex);
        }
    }
}
