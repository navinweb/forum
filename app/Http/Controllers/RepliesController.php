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

		$reply = $thread->addReply( [
			'body'    => request( 'body' ),
			'user_id' => auth()->id(),
		] );

		if(request()->expectsJson()) {
			return $reply;
		}

		return back()->with( 'flash', 'Reply has been left.' );
	}

	public function update( Reply $reply )
	{
		$this->authorize( 'update', $reply );
		$reply->update( [ 'body' => request( 'body' ) ] );
	}

	public function destroy( Reply $reply )
	{
		if ( $reply->user_id != auth()->id() ) {
			return response( [], 403 );
		}

		$reply->delete();

		if(request()->expectsJson()){
			return response(['status' => 'Reply deleted']);
		}

		return back();
	}
}
