<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadsTest extends TestCase
{
	use DatabaseMigrations;

	protected function setUp()
	{
		parent::setUp();

		Redis::del( 'trending_threads' );
	}


	/** @test */
	public function it_increments_a_threads_score_each_time_it_is_read()
	{
		$this->assertEmpty( Redis::zrevrange( 'trending_threads', 0, - 1 ) );

		$thread = create( 'App\Thread' );

		$this->call( 'GET', $thread->path() );

		$thrending = Redis::zrevrange( 'trending_threads', 0, - 1 );

		$this->assertCount( 1, $thrending );

		$this->assertEquals( $thread->title, json_decode( $thrending[0] )->title );
	}
}
