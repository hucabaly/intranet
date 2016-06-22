<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Rikkei\Team\Model\Employees;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! file_exists(RIKKEI_TEAM_PATH . 'config/user.php')) {
            return;
        }
        $dataDemo = require RIKKEI_TEAM_PATH . 'config/user.php';
        if (! $dataDemo || ! count($dataDemo)) {
            return;
        }
        DB::beginTransaction();
        try {
            if (isset($dataDemo['email']) && $dataDemo['email']) {
                $this->createEmployee($dataDemo['email']);
            }
            $this->createEmployee();
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
            $employee = new Employees();
            $employee->email = $item;
            $employee->nickname = preg_replace('/@.*$/', '', $item);
            $employee->save();
        }
    }
}
