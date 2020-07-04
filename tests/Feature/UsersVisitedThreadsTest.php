<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Class RecentVisitedThreadsTest
 *
 * @package Tests\Feature
 */
class UsersVisitedThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var UserVisit $visit
     */
    protected $visit;

    /**
     * lifecycle hook setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->be(factory('App\User')->create());
        $this->visit = resolve('UserVisit');

        $this->visit->reset();
    }

    protected function tearDown(): void
    {
        $this->visit->reset();

        parent::tearDown();
    }

    /** @test */
    public function number_of_visited_thread_gets_memorized()
    {
        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $this->assertEmpty($this->visit->get());
        $this->call('GET', $thread->path());
        $visitedThreads = $this->visit->get();

        $this->assertCount(1, $visitedThreads);
        $this->assertEquals($thread->title, ($visitedThread = current($visitedThreads))->title);
        $this->assertEquals($thread->path(), $visitedThread->path);
    }

    /** @test */
    public function user_can_see_its_top_5_most_visited_threads()
    {
        $threads = factory('App\Thread', 7)->create();
        $threads->map(function($thread) {
            $this->call('GET', $thread->path());
        });

        $visitedThreads = $this
            ->json('get', '/threads')
            ->json()['userVisitedThreads'];
        $this->assertCount(5, $visitedThreads);
        $this->assertEquals($threads->last()->path(), current($visitedThreads)['path']);
    }
}
