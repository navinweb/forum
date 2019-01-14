<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Thread;

class UserPolicy
{
    use HandlesAuthorization;

	public function update( User $user, User $signedInUser )
	{
		return $signedInUser->id === $user->id;
	}
}
