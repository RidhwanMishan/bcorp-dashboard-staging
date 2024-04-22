<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        Role::create(['name'=>'admin']);
        Role::create(['name'=>'main_ceo']);
        Role::create(['name'=>'people_overview']);
        Role::create(['name'=>'people_attendance']);
        Role::create(['name'=>'people_absenteeism']);
        Role::create(['name'=>'people_leaves']);
        Role::create(['name'=>'people_overtime']);
        Role::create(['name'=>'people_expenses']);
        Role::create(['name'=>'people_behavior']);
        Role::create(['name'=>'sales_overview']);
        Role::create(['name'=>'sales_management']);
        Role::create(['name'=>'sales_renewal']);
        Role::create(['name'=>'sales_asp']);
        Role::create(['name'=>'finance_overview']);
        Role::create(['name'=>'finance_operations']);
        Role::create(['name'=>'finance_araging']);
        Role::create(['name'=>'procurement_overview']);
        Role::create(['name'=>'operation_overview']);
        Role::create(['name'=>'supply_overview']);
        Role::create(['name'=>'sales_araging']);
    }
}
