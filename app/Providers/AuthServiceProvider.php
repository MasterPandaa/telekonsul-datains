<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('isMahasiswa', function (User $user) {
            return $user->role === 'mahasiswa';
        });

        Gate::define('isDosen', function (User $user) {
            return $user->role === 'dosen';
        });

        Gate::define('isPasien', function (User $user) {
            return $user->role === 'pasien';
        });
    }
} 