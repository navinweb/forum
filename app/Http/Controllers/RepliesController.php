<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Thread;
use App\Reply;
use Illuminate\Http\Request;
use PHPUnit\Runner\Exception;

class RepliesController extends Controller
{
	public function __construct()
	{
		$this->middleware( 'auth', [ 'except' => 'index' ] );
	}

	public function index( $channelId, Thread $thread )
	{
		return $thread->replies()->paginate( 10 );
	}

	/**
	 * @param $channelId
	 * @param Thread $thread
	 *
	 * @param Spam $spam
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( $channelId, Thread $thread, Spam $spam )
	{
		$this->validate( request(), [
			'body' => 'required',
		] );

		$spam->detect( request( 'body' ) );

		$reply = $thread->addReply( [
			'body'    => request( 'body' ),
			'user_id' => auth()->id(),
		] );

		if ( request()->expectsJson() ) {
			return $reply->load( 'owner' );
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

		if ( request()->expectsJson() ) {
			return response( [ 'status' => 'Reply deleted' ] );
		}

		return back();
	}
}
