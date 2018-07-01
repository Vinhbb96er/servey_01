<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Feedback;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the feedback.
     *
     * @param  \App\User  $user
     * @param  \App\Feedback  $feedback
     * @return mixed
     */
    public function delete(User $user, Feedback $feedback)
    {
        return true;
    }

    public function viewAll(User $user)
    {
        return true;
    }

    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
