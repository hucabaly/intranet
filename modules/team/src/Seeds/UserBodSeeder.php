<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Rikkei\Team\Model\User;
use Rikkei\Team\Model\Position;
use Rikkei\Team\Model\TeamRule;
use Rikkei\Team\View\Acl;
use Rikkei\Team\Model\TeamMembers;

class UserBodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bodTeam = DB::table('team')->where('name', 'BOD')->where('parent_id', 0)->first();
        if (! $bodTeam) {
            return;
        }
        $account = env('ACCOUNT_ROOT');
        $user = User::where('email', $account)->first();
        if  (! $user) {
            return;
        }
        $positionLevelMax = Position::orderBy('level')->first();
        if (! $positionLevelMax) {
            return;
        }
        $teamMember = TeamMembers::where('team_id', $bodTeam->id)
            ->where('user_id', $user->id)
            ->first();
        if ($teamMember) {
            $teamMember->position_id = $positionLevelMax->id;
            $teamMember->save();
        } else {
            TeamMembers::create([
                'team_id' => $bodTeam->id,
                'position_id' => $positionLevelMax->id,
                'user_id' => $user->id
            ]);
        }
        
        $rule = Acl::getAclKeyAll();
        if (! $rule) {
            return;
        }
        $dataDemo[] = [
            'position_id' => $positionLevelMax->id,
            'scope' => TeamRule::SCOPE_COMPANY,
            'rule' => $rule,
            'team_id' => $bodTeam->id
        ];
        TeamRule::saveRule($dataDemo, $bodTeam->id, true);
    }
}
