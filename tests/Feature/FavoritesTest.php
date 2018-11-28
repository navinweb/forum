<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function guests_can_not_favorite_anything()
	{
		$reply = create( 'App\Reply' );

		$this->withExceptionHandling()
		     ->post( 'replies/1/favorites' )
		     ->assertRedirect( '/login' );
	}

	/** @test */
	public function an_authenticated_users_can_favorite_any_reply()
	{
		$this->signIn();

		$reply = create( 'App\Reply' );

		$this->post( 'replies/' . $reply->id . '/favorites' );

		$this->assertCount( 1, $reply->favorites );
	}

	/** @test */
	public function an_authenticated_users_can_unfavorite_a_reply()
	{
		$this->signIn();

		$reply = create( 'App\Reply' );

		$reply->favorite();

		$this->delete( 'replies/' . $reply->id . '/favorites' );

		$this->assertCount( 0, $reply->fresh()->favorites );
	}

	/** @test */
	public function an_authenticated_users_may_favorite_a_reply_once()
	{
		$this->signIn();

		$reply = create( 'App\Reply' );

		try {
			$this->post( 'replies/' . $reply->id . '/favorites' );
			$this->post( 'replies/' . $reply->id . '/favorites' );
		}
		catch (\Exception $e) {
			$this->fail('Did not expect to insert the same records twice');
		}

		$this->assertCount( 1, $reply->favorites );
	}
}
