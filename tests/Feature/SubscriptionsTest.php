<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function authenticated_user_can_subscribe_a_thread()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();

        $this->json('post', $thread->path('subscribes'));

        $this->assertEquals(
            1, $thread->fresh()->subscribes()->where('user_id', auth()->id())->count()
        );
    }

    /** @test */
    public function authenticated_user_can_unsubscribe_a_thread()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $otherThread = factory('App\Thread')->create();

        $this->json('post', $thread->path('subscribes'));
        $this->json('post', $otherThread->path('subscribes'));

        $this->json('delete', $thread->path('subscribes'));

        $this->assertEquals(
            0, $thread->fresh()->subscribes()->where('user_id', auth()->id())->count()
        );
        $this->assertEquals(
            1, $otherThread->fresh()->subscribes()->where('user_id', auth()->id())->count()
        );
    }
}
