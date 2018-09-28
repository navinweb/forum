<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function unauthenticated_user_may_not_add_replies()
	{
		$this->expectException( 'Illuminate\Auth\AuthenticationException' );

		create( 'App\Thread' );

		$this->post( '/threads/1/replies', [] );
	}

	/** @test */
	public function an_authenticated_user_may_participate_in_forum_thread()
	{
		$this->be( $user = create( 'App\User' ) );
		$thread = create( 'App\Thread' );
		$reply  = make( 'App\Reply' );

		$this->post( $thread->path() . '/replies', $reply->toArray() );

		$this->get( $thread->path() )
		     ->assertSee( $reply->body );
	}
}
