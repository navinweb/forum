<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;

class FavoritesController extends Controller
{
	function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * @param Reply $reply
	 */
	public function store( Reply $reply )
	{
		$reply->favorite();

		return back();
	}

	public function destroy( Reply $reply )
	{
		$reply->unfavorite();
	}
}
