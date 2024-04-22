<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-users', function($user){
            return $user->hasAnyRoles(['admin']);
        });
        Gate::define('add-users', function($user){
            return $user->hasRole('admin');
        });
        Gate::define('edit-users', function($user){
            return $user->hasRole('admin');
        });
        Gate::define('delete-users', function($user){
            return $user->hasRole('admin');
        });
        Gate::define('main_ceo', function($user){
            return $user->hasRole('main_ceo');
        });
        Gate::define('people_overview', function($user){
            return $user->hasRole('people_overview');
        });
        Gate::define('people_attendance', function($user){
            return $user->hasRole('people_attendance');
        });
        Gate::define('people_absenteeism', function($user){
            return $user->hasRole('people_absenteeism');
        });
        Gate::define('people_leaves', function($user){
            return $user->hasRole('people_leaves');
        });
        Gate::define('people_overtime', function($user){
            return $user->hasRole('people_overtime');
        });
        Gate::define('people_expenses', function($user){
            return $user->hasRole('people_expenses');
        });
        Gate::define('people_behavior', function($user){
            return $user->hasRole('people_behavior');
        });
        Gate::define('sales_overview', function($user){
            return $user->hasRole('sales_overview');
        });
        Gate::define('sales_management', function($user){
            return $user->hasRole('sales_management');
        });
        Gate::define('sales_renewal', function($user){
            return $user->hasRole('sales_renewal');
        });
        Gate::define('sales_asp', function($user){
            return $user->hasRole('sales_asp');
        });
        Gate::define('finance_overview', function($user){
            return $user->hasRole('finance_overview');
        });
        Gate::define('finance_operations', function($user){
            return $user->hasRole('finance_operations');
        });
        Gate::define('finance_araging', function($user){
            return $user->hasRole('finance_araging');
        });
        Gate::define('procurement_overview', function($user){
            return $user->hasRole('procurement_overview');
        });
        Gate::define('operation_overview', function($user){
            return $user->hasRole('operation_overview');
        });
        Gate::define('supply_overview', function($user){
            return $user->hasRole('supply_overview');
        });

    }
}
