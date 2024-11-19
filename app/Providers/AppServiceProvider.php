<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('read-task', function (User $user) {
            if($user->role_id == 1){
                return true;
            }
            return false;
        });

        Gate::define('read-by-id-task', function (User $user, Task $task) {
            return $user->id == $task->user_id || $user->id === 1;
        });

        Gate::define('update-task', function (User $user, Task $task) {
            return $user->id === $task->user_id || $user->id === 1;
        });

        Gate::define('deleted-task', function (User $user, Task $task) {
            return $user->id === $task->user_id || $user->id === 1;
        });
    }
}
