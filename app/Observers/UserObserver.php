<?php

namespace App\Observers;

use App\Models\Server;
use App\Models\User;
use App\Services\Helper;
use App\Services\Pear2Service;

class UserObserver
{

    public function created(User $user): void
    {
        //
    }

    public function updating(User $user)
    {
        if ($user->isDirty('is_active_client') || $user->isDirty('password') || $user->isDirty('package_id'))
        {
            User::synchronize($user);
        }
    }

    public function updated(User $user): void
    {

    }


    public function deleted(User $user): void
    {
        //
    }

    public function restored(User $user): void
    {
        //
    }

    public function forceDeleted(User $user): void
    {
        //
    }
}
