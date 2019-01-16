<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Thread;
use App\Filters\ThreadFilters;
use App\Trending;
use Illuminate\Http\Request;
use App\Channel;

class ThreadsController extends Controller
{

	/**
	 * ThreadsController constructor.
	 */
	public function __construct()
	{
		$this->middleware( 'auth' )->except( 'show', 'index' );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param Channel $channel
	 *
	 * @param ThreadFilters $filters
	 *
	 * @param Trending $trending
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index( Channel $channel, ThreadFilters $filters, Trending $trending )
	{
		$threads = $this->getThreads( $channel, $filters );

		if ( request()->wantsJson() ) {
			return $threads;
		}

		return view( 'threads.index', [
			'threads'  => $threads,
			'trending' => $trending->get()
		] );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view( 'threads.create' );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @param Spam $spam
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request )
	{
		request()->validate( [
			'title'      => 'required|spamfree',
			'body'       => 'required|spamfree',
			'channel_id' => 'required|exists:channels,id',
		] );

		$thread = Thread::create( [
			'user_id'    => auth()->id(),
			'channel_id' => request( 'channel_id' ),
			'title'      => request( 'title' ),
			'body'       => request( 'body' ),
		] );

		return redirect( $thread->path() )
			->with( 'flash', 'Thread has been published' );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param $channel
	 * @param  \App\Thread $thread
	 *
	 * @param Trending $trending
	 *
	 * @return \Illuminate\Http\Response
	 * @internal param $channelId
	 */
	public function show( $channel, Thread $thread, Trending $trending )
	{
		if ( auth()->check() ) {
			auth()->user()->read( $thread );
		}

		$trending->push($thread);

		$thread->recordVisit();

		return view( 'threads.show', compact( 'thread' ) );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Thread $thread
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( Thread $thread )
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\Thread $thread
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, Thread $thread )
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Channel $channel
	 * @param  \App\Thread $thread
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( Channel $channel, Thread $thread )
	{
		$this->authorize( 'update', $thread );

		$thread->delete();

		if ( request()->wantsJson() ) {
			return response( [], 204 );
		}

		return redirect( '/threads' );
	}

	/**
	 * @param Channel $channel
	 * @param ThreadFilters $filters
	 *
	 * @return mixed
	 */
	protected function getThreads( Channel $channel, ThreadFilters $filters )
	{
		$threads = Thread::latest()->filter( $filters );

		if ( $channel->exists ) {
			$threads->where( 'channel_id', $channel->id );
		}

		$threads = $threads->paginate( 15 );

		return $threads;
	}
}
