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

		$this->post( "/threads/{$thread->channel->slug}/{$thread->slug}/replies", [] );
	}

	/** @test */
	public function an_authenticated_user_may_participate_in_forum_thread()
	{
		$this->withExceptionHandling()->signIn();

		$thread = create( 'App\Thread' );

		$reply  = make( 'App\Reply' );

		$this->post( $thread->path() . '/replies', $reply->toArray() );

		$this->assertDatabaseHas( 'replies', [ 'body' => $reply->body ] );

		$this->assertEquals( 1, $thread->fresh()->replies_count );
	}

	/** @test */
	public function a_reply_requires_a_body()
	{
		$this->withExceptionHandling()->signIn();

		$thread = create( 'App\Thread' );

		$reply = make( 'App\Reply', [ 'body' => null ] );

		$this->json( 'post',$thread->path() . '/replies', $reply->toArray() )
		     ->assertStatus( 422 );
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
	public function authorized_users_can_delete_replies()
	{
		$this->signIn();

		$reply = create( 'App\Reply', [ 'user_id' => auth()->id() ] );

		$this->delete( "/replies/{$reply->id}" )->assertStatus( 302 );

		$this->assertDatabaseMissing( 'replies', [ 'id' => $reply->id ] );

		$this->assertEquals( 0, $reply->thread->fresh()->replies_count );
	}

	/** @test */
	public function unauthorized_users_cannot_update_replies()
	{
		$this->withExceptionHandling();

		$reply = create( 'App\Reply' );

		$this->patch( "/replies/{$reply->id}" )
		     ->assertRedirect( 'login' );

		$this->signIn()
		     ->patch( "/replies/{$reply->id}" )
		     ->assertStatus( 403 );
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

	/** @test */
	public function replies_that_contains_spam_may_not_be_created()
	{
		$this->withExceptionHandling();
		$this->signIn();

		$thread = create( 'App\Thread' );

		$reply = make( 'App\Reply', [
			'body' => 'Yahoo Customer Support'
		] );

		$this->json( 'post', $thread->path() . '/replies', $reply->toArray() )
		     ->assertStatus( 422 );
	}

	/** @test */
	public function users_may_only_reply_a_maximum_of_once_per_minute()
	{
		$this->withExceptionHandling();

		$this->signIn();

		$thread = create( 'App\Thread' );
		$reply  = make( 'App\Reply' );

		$this->post( $thread->path() . '/replies', $reply->toArray() )
		     ->assertStatus( 201 );

		$this->post( $thread->path() . '/replies', $reply->toArray() )
		     ->assertStatus( 429 );
	}
}
