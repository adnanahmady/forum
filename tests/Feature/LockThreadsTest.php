<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function locked_threads_can_not_have_new_replies()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $thread->setLock();

        $this->post($thread->path('replies'), [
            'body' => 'some body'
        ])->assertStatus(422);
    }

    /** @test */
    public function non_administrator_may_not_lock_threads()
    {
        $this->be(factory('App\User')->state('non-admin')->create());
        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $this->json('post', route('thread.lock', $thread))
            ->assertStatus(403);

        $this->assertNull($thread->fresh()->locked);
    }

    /** @test */
    public function administrator_may_lock_threads()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $this
            ->json('post', route('thread.lock', $thread));

        $this->assertNotNull($thread->fresh()->locked);
    }
    
    /** @test */
    public function non_administrator_may_not_unlock_threads()
    {
        $this->be(factory('App\User')->state('non-admin')->create());
        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $this
            ->json('post', route('thread.unlock', $thread))
            ->assertStatus(403);

        $this->assertNull($thread->fresh()->locked);
    }

    /** @test */
    public function administrator_may_unlock_threads()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')
            ->state('locked')
            ->create(['user_id' => auth()->id()]);

        $this
            ->json('delete', route('thread.unlock', $thread));

        $this->assertNull($thread->fresh()->locked);
    }
}
