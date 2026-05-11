<?php
// app/Policies/RequestPolicy.php

namespace App\Policies;

use App\Models\TitipRequest;
use App\Models\User;

class RequestPolicy
{
    public function update(User $user, TitipRequest $titipRequest)
    {
        return $user->id === $titipRequest->user_id;
    }

    public function delete(User $user, TitipRequest $titipRequest)
    {
        return $user->id === $titipRequest->user_id;
    }
}