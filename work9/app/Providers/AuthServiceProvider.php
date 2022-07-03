<?php

namespace App\Providers;

use App\Models\User;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-only', function (User $user) {
            return $user->position === 'admin';
        });
        Gate::define('director-only', function (User $user) {
            if ($user->position == 'admin' || $user->position == 'director') {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('managers-only', function (User $user) {
            if ($user->position == 'admin' || $user->position == 'director' || $user->position == 'manager') {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('tech-executive-only', function (User $user) {
            if ($user->position == 'admin' ||
                $user->position == 'director' ||
                $user->position == 'manager' ||
                $user->position == 'technical-executive') {
                return true;
            } else {
                return false;
            }
        });
        Gate::define('sale-executive-only', function (User $user) {
            if ($user->position == 'admin' ||
                $user->position == 'director' ||
                $user->position == 'manager' ||
                $user->position == 'sales-executive') {
                return true;
            } else {
                return false;
            }
        });
        Gate::define('store-keeper-only', function (User $user) {
            if ($user->position == 'admin' ||
                $user->position == 'director' ||
                $user->position == 'store-keeper' ||
                $user->position == 'manager' ||
                $user->position == 'technical-executive' ||
                $user->position == 'sales-executive' ) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define('supplier-only', function (User $user) {
            if ($user->position == 'admin' ||
                $user->position == 'director' ||
                $user->position == 'supplier' ) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define('banker-only', function (User $user) {
            if ($user->position == 'admin' ||
                $user->position == 'director' ||
                $user->position == 'banker' ) {
                return true;
            } else {
                return false;
            }
        });
    }
}
