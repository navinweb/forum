<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * @param $channelId
	 * @param Thread $thread
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( $channelId, Thread $thread )
	{
		$this->validate( request(), [
			'body' => 'required',
		] );

		$thread->addReply( [
			'body'    => request( 'body' ),
			'user_id' => auth()->id(),
		] );

		return back()->with( 'flash', 'Reply has been left.' );
	}

	public function destroy( Reply $reply )
	{
		if ( $reply->user_id != auth()->id() ) {
			return response( [], 403 );
		}

		$reply->delete();
		return back();
	}
}
