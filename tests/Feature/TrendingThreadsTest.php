<?php

namespace Tests\Feature;

use App\Trending;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadsTest extends TestCase
{
	use DatabaseMigrations;

	protected function setUp()
	{
		parent::setUp();

		$this->trending = new Trending();

		$this->trending->reset();
	}


	/** @test */
	public function it_increments_a_threads_score_each_time_it_is_read()
	{
		$this->assertEmpty( $this->trending->get() );

		$thread = create( 'App\Thread' );

		$this->call( 'GET', $thread->path() );

		$this->assertCount( 1, $thrending = $this->trending->get() );

		$this->assertEquals( $thread->title, $thrending[0]->title );
	}
}
