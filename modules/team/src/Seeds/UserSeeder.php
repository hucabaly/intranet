<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Rikkei\Team\Model\Employees;
use Rikkei\Team\Model\Team;
use Rikkei\Team\Model\Roles;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userDataFilePath = RIKKEI_TEAM_PATH . 'data-sample' . DIRECTORY_SEPARATOR . 'seed' . 
                DIRECTORY_SEPARATOR .  'user.php';
        if (! file_exists($userDataFilePath)) {
            return;
        }
        $dataDemo = require $userDataFilePath;
        if (! $dataDemo || ! count($dataDemo)) {
            return;
        }
        DB::beginTransaction();
        try {
            if (isset($dataDemo) && $dataDemo) {
                $this->createEmployee($dataDemo);
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
    protected function createEmployee($data)
    {
        foreach ($data as $item) {
            $employee = Employees::where('email', $item['email'])->first();
            //add employee
            if (! $employee) {
                $employee = new Employees();
                $employee->email = $item['email'];
                $employee->nickname = preg_replace('/@.*$/', '', $item['email']);
                $employee->save();
            }
            //add team and position
            $team = Team::where('name', $item['team'])->first();
            $role = Roles::where('role', $item['role'])->where('special_flg', Roles::FLAG_POSITION)->first();
            if (! $team || ! $role) {
                continue;
            }
            $employee->saveTeamPosition([
                [
                    'team' => $team->id,
                    'position' => $role->id,
                ]
            ]);
        }
    }
}
