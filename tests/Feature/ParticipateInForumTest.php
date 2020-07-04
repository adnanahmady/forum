<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_can_not_add_reply_to_a_thread()
    {
        $this
            ->withoutExceptionHandling()
            ->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads/channel/1/replies', []);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();
        $this->post($thread->path('replies'), $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    public function body_is_required_for_adding_replies()
    {
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->raw(['body' => null]);

        $this->post($thread->path('replies'), $reply)
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function body_can_not_be_empty_for_adding_replies()
    {
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->raw(['body' => '']);

        $this->post($thread->path('replies'), $reply)
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function users_can_create_a_reply_every_one_minute()
    {
        $this->be(factory('App\User')->create(['name' => 'someUser']));

        $thread = factory('App\Thread')->create();

        $this->json('post', $thread->path('replies'), ['body' => 'some reply'])
            ->assertStatus(201);

        $this->json('post', $thread->path('replies'), ['body' => 'some reply'])
            ->assertStatus(429);

        $this->assertCount(1, $thread->fresh()->replies);
    }
}
