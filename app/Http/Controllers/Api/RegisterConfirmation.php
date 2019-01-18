<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmation extends Controller
{
	public function index()
	{
		$user            = User::where( 'confirmation_token', request( 'token' ) )
		                       ->firstOrFail();
		$user->confirm();

		return redirect('/threads')
			->with('flash', 'Your account is now confirmed! You may post to the forum.');
	}
}
