<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        DB::table('role_user')->truncate();

        $adminRole = Role::where('name', 'admin')->first();
        $ceoRole = Role::where('name', 'main_ceo')->first();

        $people_overview= Role::where('name','people_overview')->first();
        $people_attendance= Role::where('name','people_attendance')->first();
        $people_absenteeism= Role::where('name','people_absenteeism')->first();
        $people_leaves= Role::where('name','people_leaves')->first();
        $people_overtime= Role::where('name','people_overtime')->first();
        $people_expenses= Role::where('name','people_expenses')->first();
        $people_behavior= Role::where('name','people_behavior')->first();

        $sales_overview= Role::where('name','sales_overview')->first();
        $sales_management= Role::where('name','sales_management')->first();
        $sales_renewal= Role::where('name','sales_renewal')->first();
        $sales_asp= Role::where('name','sales_asp')->first();

        $finance_overview= Role::where('name','finance_overview')->first();

        $procurement_overview= Role::where('name','procurement_overview')->first();

        $operation_overview= Role::where('name','operation_overview')->first();

        $supply_overview= Role::where('name','supply_overview')->first();

        $admin = User::create(['name'=>'Aqib', 'email'=>'aqib.abas@lloopp.net', 'landing_page'=>'main.ceo' ,'designation'=>'CTO', 'division'=>'People' ,'department'=>'biz enabler','password'=>Hash::make('qwerty123')]);
        $ceo = User::create(['name'=>'CEO User', 'email'=>'farouk@lloopp.net', 'landing_page'=>'main.ceo' ,'designation'=>'CEO', 'division'=>'People' ,'department'=>'biz enabler','password'=>Hash::make('password')]);

        $admin->roles()->attach($adminRole);
        $admin->roles()->attach($ceoRole);
        $admin->roles()->attach($people_absenteeism);
        $admin->roles()->attach($people_attendance);
        $admin->roles()->attach($people_behavior);
        $admin->roles()->attach($people_expenses);
        $admin->roles()->attach($people_leaves);
        $admin->roles()->attach($people_overtime);
        $admin->roles()->attach($people_overview);
        $admin->roles()->attach($sales_asp);
        $admin->roles()->attach($sales_management);
        $admin->roles()->attach($sales_overview);
        $admin->roles()->attach($sales_renewal);
        $admin->roles()->attach($finance_overview);
        $admin->roles()->attach($procurement_overview);
        $admin->roles()->attach($operation_overview);
        $admin->roles()->attach($supply_overview);





        $ceo->roles()->attach($ceoRole);
    }
}
