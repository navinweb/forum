<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Inspections\Spam;
use App\Thread;
use App\Reply;
use Gate;
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
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( $channelId, Thread $thread, CreatePostRequest $form )
	{
		return $thread->addReply( [
			'body'    => request( 'body' ),
			'user_id' => auth()->id(),
		] )->load( 'owner' );
	}

	/**
	 * @param Reply $reply
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function update( Reply $reply )
	{
		$this->authorize( 'update', $reply );

		try {
			$reply->update( request()->validate( [ 'body' => 'required|spamfree' ] ) );
		} catch ( \Exception $e ) {
			return response( 'Sorry, your reply could not saved at this time.', 422 );
		}
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
