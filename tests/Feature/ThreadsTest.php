<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    private $endPoint = '/threads';
    private $thread;

    private function path()
    {
        return implode('/', array_merge([$this->endPoint], func_get_args()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        $this->get($this->path())
            ->assertStatus(200)
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_visit_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertStatus(200)
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_users_name()
    {
        $this->be(factory('App\User')->create());

        $threadByUser = factory('App\Thread')->create(['user_id' => auth()->id()]);
        $threadNotByUser = factory('App\Thread')->create();

        $this->get('/threads?by=' . auth()->user()->name)
            ->assertSee($threadByUser->title)
            ->assertDontSee($threadNotByUser->title);
    }

    /** @test */
    public function it_can_be_filtered_by_unanswereds()
    {
        $this->withoutExceptionHandling();
        $answeredThread = factory('App\Thread')->create();
        $unansweredThread = factory('App\Thread')->create();
        factory('App\Reply')->create(['thread_id' => $answeredThread->id]);

        $this
            ->get("/threads?unanswered=1")
            ->assertDontSee($answeredThread->body)
            ->assertSee($unansweredThread->body)
        ;
    }

    /** @test */
    public function threads_can_be_ordered_by_most_replies()
    {
        $this->assertEquals([3, 2, 0], array_column($this->getReplies(1), 'replies_count'));
    }

    /** @test */
    public function threads_can_be_ordered_by_least_replies()
    {
        $this->assertEquals([0,2,3], array_column($this->getReplies(-1), 'replies_count'));
    }

    protected function getReplies($order = '')
    {
        $threadsWithThreeReplies = factory('App\Thread')->create();
        factory('App\Reply', 3)->create(['thread_id' => $threadsWithThreeReplies->id]);

        $threadsWithTwoReplies = factory('App\Thread')->create();
        factory('App\Reply', 2)->create(['thread_id' => $threadsWithTwoReplies->id]);

        $threadsWithNoReplies = $this->thread;

        $query = !empty($order) ? "={$order}" : '';

        $response = $this->getJson("/threads?replies{$query}")
            ->json()['threads']
        ;

        return $response['data'];
    }
    
    /** @test */
    public function a_thread_body_is_sanitized_automatically()
    {
        $allowedElement = "<p>this is allowed</p>";
        $body = "<script>alert('alert injected')</script>$allowedElement";
        $thread = factory('App\Thread')->make(['body' => $body]);

        $this->assertEquals($thread->body, $allowedElement);
    }
}
