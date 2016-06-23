<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Rikkei\Team\Model\Employees;
use Rikkei\Team\Model\Team;
use Rikkei\Team\Model\Roles;
use Rikkei\Team\Model\Permissions;
use Rikkei\Team\View\Acl;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataFilePath = RIKKEI_TEAM_PATH . 'data-sample' . DIRECTORY_SEPARATOR . 'seed' . 
                DIRECTORY_SEPARATOR .  'permission.php';
        if (! file_exists($dataFilePath)) {
            return;
        }
        $dataDemo = require $dataFilePath;
        if (! $dataDemo || ! count($dataDemo)) {
            return;
        }
        DB::beginTransaction();
        try {
            if (isset($dataDemo) && $dataDemo) {
                $this->createPermission($dataDemo);
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * create employee demo
     * 
     * @param array $data
     */
    protected function createPermission($data)
    {
        //get acl
        $acl = Acl::getAclList();
        if (! count($acl)) {
            return;
        }
        $aclDataId = [];
        foreach ($acl as $aclKey => $aclValue) {
            if (isset($aclValue['child']) && count($aclValue['child'])) {
                foreach ($aclValue['child'] as $aclItemKey => $aclItem) {
                    $aclDataId[] = $aclItemKey;
                }
            }
        }
        if (! $aclDataId || ! count($aclDataId)) {
            return;
        }
        
        foreach ($data as $item) {
            $team = Team::where('name', $item['team'])->first();
            $role = Roles::where('role', $item['role'])->where('special_flg', Roles::FLAG_POSITION)->first();
            if (! $team || ! $role) {
                continue;
            }
            $itemData = [];
            foreach ($aclDataId as $aclId) {
                $itemData[] = [
                    'team_id' => $team->id,
                    'role_id' => $role->id,
                    'action_id' => $aclId,
                    'scope' => $item['scope']
                ];
            }
            Permissions::saveRule($itemData, $team->id);
        }
    }
}
