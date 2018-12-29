<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_current_user(
	)
	{
		$this->signIn();

		$thread = create( 'App\Thread' )->subscribe();

		$thread->addReply( [
			'user_id' => auth()->id(),
			'body'    => 'Some reply'
		] );

		$this->assertCount( 0, auth()->user()->fresh()->notifications );

		$thread->addReply( [
			'user_id' => create( 'App\User' )->id,
			'body'    => 'Some reply'
		] );

		$this->assertCount( 1, auth()->user()->fresh()->notifications );
	}
}
