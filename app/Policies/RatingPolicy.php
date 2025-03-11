<?php

namespace App\Policies;

use App\Models\Rating;
use App\Models\User;

class RatingPolicy
{
    public function create(User $user): bool
    {
        return in_array('rater', $user->roles);
    }
}
