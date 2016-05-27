<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Rikkei\Team\Model\Team;
use Rikkei\Team\Model\User;
use Rikkei\Team\Model\Position;
use Rikkei\Team\Model\TeamRule;
use Rikkei\Team\View\Acl;

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
        $user->update([
            'team_id' => $bodTeam->id,
            'position_id' => $positionLevelMax->id
        ]);
        
        $rules = Acl::getAclKey();
        if (! $rules) {
            return;
        }
        $dataDemo = [];
        foreach ($rules as $rule) {
            $dataDemo[] = [
                'position_id' => $positionLevelMax->id,
                'scope' => TeamRule::SCOPE_COMPANY,
                'rule' => $rule,
                'team_id' => $bodTeam->id
            ];
        }
        DB::beginTransaction();
        try {
            TeamRule::saveRule($dataDemo, $bodTeam->id, true);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        
    }
}
