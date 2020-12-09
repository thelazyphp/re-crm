<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
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

        Gate::define('update-team', function (User $user, Team $team) {
            $user->ownsTeam($team);
        });

        Gate::define('delete-team', function (User $user, Team $team) {
            $user->ownsTeam($team);
        });

        Gate::define('create-team-member', function (User $user, Team $team) {
            $user->ownsTeam($team);
        });

        Gate::define('update-team-member', function (User $user, Team $team, User $teamMember) {
            $user->ownsTeam($team) && $teamMember->belongsToTeam($team);
        });

        Gate::define('delete-team-member', function (User $user, Team $team, User $teamMember) {
            $user->ownsTeam($team) && $teamMember->belongsToTeam($team);
        });

        Passport::routes();

        //
    }
}
