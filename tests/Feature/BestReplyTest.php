<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);
        $replies = factory('App\Reply', 2)->create(['thread_id' => $thread->id]);

        $this->postJson(route('best-replies.store', ['reply' => $replies[1]]));
        $this->assertTrue($replies[1]->fresh()->isBest());
        $this->assertFalse($replies[0]->fresh()->isBest());
    }

    /** @test */
    public function it_can_be_choosen_only_be_the_thread_creator()
    {
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);
        $replies = factory('App\Reply', 2)->create(['thread_id' => $thread->id]);

        $this->be(factory('App\User')->create());
        $this->assertFalse($replies[1]->isBest());
        $this->postJson(route('best-replies.store', ['reply' => $replies[1]]))
            ->assertStatus(403);
        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function by_delete_best_reply_thread_knows()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());

        $reply = factory('App\Reply')->create(['user_id' => auth()->id()]);
        $reply->thread->bestReply($reply);
        $thread = $reply->thread;

        $this->deleteJson(route('reply.destroy', $reply));

        $this->assertNull($thread->fresh()->best_reply_id);
    }
}
