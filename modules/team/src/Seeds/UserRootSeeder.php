<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use Rikkei\Team\Model\User;
use Rikkei\Team\Model\TeamRule;
use Rikkei\Team\View\Acl;
use Rikkei\Core\Model\Employee;
use Rikkei\Team\Model\Roles;
use Rikkei\Team\Model\EmployeeRole;

class UserRootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = env('ACCOUNT_ROOT');
        if (! $email) {
            return;
        }
        $user = User::where('email', $email)->first();
        $employee = Employee::where('email', $email)->first();
        $name = preg_replace('/@.*$/', '', $email);
        if (! $employee) {
            $employee = Employees::create([
                'email' => $user->email,
                'name' => $name,
                'nickname' => $name
            ]);
        }
        if (! $user) {
            $user = User::create([
                'email' => $user->email,
                'name' => $name,
                'nickname' => $name,
                'employee_id' => $employee->id
            ]);
        } else {
            if (! $user->employee_id) {
                $user->employee_id = $employee->id;
            }
            $user->save();
        }
        
        $rule = Acl::getAclKeyAll();
        if (! $rule) {
            return;
        }
        $roles = Roles::where('name', 'Root')->first();
        if (! $roles) {
            $roles = Roles::create([
                'name' => 'Root'
            ]);
        }
        EmployeeRole::where('employee_id', $employee->id)->delete();
        EmployeeRole::create([
            'employee_id' => $employee->id,
            'role_id' => $roles->id
        ]);
        $roles->save([
            [
                'rule' => $rule,
                'scope' => TeamRule::SCOPE_COMPANY
            ]
        ]);
    }
}
