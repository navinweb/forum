<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInThreadTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function unauthenticated_user_may_not_add_replies()
	{
		$this->expectException( 'Illuminate\Auth\AuthenticationException' );

		$thread = create( 'App\Thread' );

		$this->post( "/threads/{$thread->channel->slug}/{$thread->id}/replies", [] );
	}

	/** @test */
	public function an_authenticated_user_may_participate_in_forum_thread()
	{
		$this->withExceptionHandling()->signIn();
		$thread = create( 'App\Thread' );
		$reply  = make( 'App\Reply' );

		$this->post( $thread->path() . '/replies', $reply->toArray() );

		$this->get( $thread->path() )
		     ->assertSee( $reply->body );
	}

	/** @test */
	public function a_reply_requires_a_body()
	{
		$this->withExceptionHandling()->signIn();

		$thread = create( 'App\Thread' );
		$reply  = make( 'App\Reply', [ 'body' => null ] );

		$this->post( $thread->path() . '/replies', $reply->toArray() )
		     ->assertSessionHasErrors();
	}

	/** @test */
	public function unauthorized_users_cannot_delete_replies()
	{
		$this->withExceptionHandling();

		$reply = create( 'App\Reply' );

		$this->delete( "/replies/{$reply->id}" )
		     ->assertRedirect( 'login' );

		$this->signIn()
		     ->delete( "/replies/{$reply->id}" )
		     ->assertStatus( 403 );
	}

	/** @test */
	public function authorized_users_cannot_delete_replies()
	{
		$this->signIn();

		$reply = create( 'App\Reply', [ 'user_id' => auth()->id() ] );

		$this->delete( "/replies/{$reply->id}" )->assertStatus( 302 );

		$this->assertDatabaseMissing( 'replies', [ 'id' => $reply->id ] );
	}

	/** @test */
	public function authorized_users_can_update_replies()
	{
		$this->signIn();

		$reply = create( 'App\Reply', [ 'user_id' => auth()->id() ] );

		$updatedReply = 'Changed';
		$this->patch( "/replies/{$reply->id}", [ 'body' => $updatedReply ] );

		$this->assertDatabaseHas( 'replies', [ 'id' => $reply->id, 'body' => $updatedReply ] );
	}
}
