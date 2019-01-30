<?php

namespace Tests\Feature;

use App\Activity;
use App\Rules\Recaptcha;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();

		app()->singleton( Recaptcha::class, function () {
			return \Mockery::mock( Recaptcha::class, function ( $m ) {
				$m->shouldReceive( 'passes' )->andReturn( true );
			} );
		} );
	}

	/** @test */
	public function guest_may_not_create_threads()
	{
		$this->withExceptionHandling();

		$this->get( '/threads/create' )
		     ->assertRedirect( route( 'login' ) );

		//				$this->post( route( 'threads' ) )
		//				     ->assertRedirect( route( 'login' ) );
	}

	/** @test */
	public function new_users_must_first_confirmtheir_email_address_before_creating_threads()
	{
		$user = factory( 'App\User' )->states( 'unconfirmed' )->create();

		$this->signIn( $user );

		$thread = make( 'App\Thread' );

		$this->post( route( 'threads' ), $thread->toArray() )
		     ->assertRedirect( route( 'threads' ) )
		     ->assertSessionHas( 'flash', 'You must first confirm your email address.' );
	}

	/** @test */
	public function an_user_can_create_new_forum_threads()
	{
		$response = $this->publishThread( [ 'title' => 'Some Title', 'body' => 'Some body.' ] );

		$this->get( $response->headers->get( 'Location' ) )
		     ->assertSee( 'Some Title' )
		     ->assertSee( 'Some body.' );
	}

	/** @test */
	public function a_thread_requires_a_title()
	{
		$this->publishThread( [ 'title' => null ] )
		     ->assertSessionHasErrors( 'title' );
	}

	/** @test */
	public function a_thread_requires_a_body()
	{
		$this->publishThread( [ 'body' => null ] )
		     ->assertSessionHasErrors( 'body' );
	}

	/** @test */
	public function a_thread_requires_a_valid_channel()
	{
		factory( 'App\Channel', 2 )->create();

		$this->publishThread( [ 'channel_id' => null ] )
		     ->assertSessionHasErrors( 'channel_id' );

		$this->publishThread( [ 'channel_id' => 999 ] )
		     ->assertSessionHasErrors( 'channel_id' );
	}

	/** @test */
	public function a_thread_requires_recaptcha_verification()
	{
		unset( app()[ Recaptcha::class ] );

		$this->publishThread( [ 'g-recaptcha-response' => 'test' ] )
		     ->assertSessionHasErrors( 'g-recaptcha-response' );
	}

	/** @test */
	public function a_thread_requires_a_unique_slug()
	{
		$this->signIn();

		$thread = create( 'App\Thread', [ 'title' => 'Foo Title' ] );

		$this->assertEquals( $thread->fresh()->slug, 'foo-title' );

		$thread = $this->postJson( route( 'threads' ),
			$thread->toArray() + [ 'g-recaptcha-response' => 'token' ] )->json();

		$this->assertEquals( "foo-title-{$thread['id']}", $thread['slug'] );
	}


	/** @test */
	public function a_thread_with_a_title_that_ends_in_number_should_generate_the_proper_slug()
	{
		$this->signIn();

		$thread = create( 'App\Thread', [ 'title' => 'Title 24' ] );

		$thread = $this->postJson( route( 'threads' ),
			$thread->toArray() + [ 'g-recaptcha-response' => 'token' ] )->json();

		$this->assertEquals( "title-24-{$thread['id']}", $thread['slug'] );
	}

	/** @test */
	public function unauthorized_users_may_not_delete_threads()
	{
		$this->withExceptionHandling();
		$thread = create( 'App\Thread' );

		$this->delete( $thread->path() )
		     ->assertRedirect( '/login' );

		$this->signIn();

		$this->delete( $thread->path() )
		     ->assertStatus( 403 );
	}

	/** @test */
	public function authorized_user_can_delete_threads()
	{
		$this->signIn();

		$thread = create( 'App\Thread', [ 'user_id' => auth()->id() ] );
		$reply  = create( 'App\Reply', [ 'thread_id' => $thread->id ] );

		$response = $this->json( 'DELETE', $thread->path() );

		$response->assertStatus( 204 );

		$this->assertDatabaseMissing( 'threads', [ 'id' => $thread->id ] );
		$this->assertDatabaseMissing( 'replies', [ 'id' => $reply->id ] );
		$this->assertEquals( 0, Activity::count() );
	}

	public function publishThread( $overrides )
	{
		$this->withExceptionHandling()->signIn();

		$thread = make( 'App\Thread', $overrides );

		return $this->post( route( 'threads' ), $thread->toArray() + [ 'g-recaptcha-response' => 'token' ] );
	}
}
