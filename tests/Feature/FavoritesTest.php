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
	public function an_authenticated_users_can_favorite_any_replay()
	{
		$this->signIn();

		$reply = create( 'App\Reply' );

		$this->post( 'replies/' . $reply->id . '/favorites' );

		$this->assertCount( 1, $reply->favorites );
	}
}
