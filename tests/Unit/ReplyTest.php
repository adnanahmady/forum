<?php

namespace Tests\Unit;

use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function reply_has_an_owner()
    {
        $reply = factory(Reply::class)->create();

        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function guest_cant_delete_reply()
    {
        $reply = factory('App\Reply')->create();

        $this->delete("replies/{$reply->id}")
            ->assertRedirect('login');
    }

    /** @test */
    public function authenticated_users_cant_delete_other_users_replies()
    {
        $this->be(factory('App\User')->create(['name' => 'admin']));
        $reply = factory('App\Reply')->create();

        $this->delete("replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_their_replies()
    {
        $this->be(factory('App\User')->create());
        $reply = factory('App\Reply')->create(['user_id' => auth()->id()]);

        $this->delete("replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function guest_cant_update_reply()
    {
        $reply = factory('App\Reply')->create();
        $this->patch("replies/{$reply->id}", [
            'body' => factory('App\Reply')->make()->body
        ])
            ->assertRedirect('login');
    }


    /** @test */
    public function authenticated_users_cant_update_other_users_replies()
    {
        $this->be(factory('App\User')->create(['name' => 'random']));
        $reply = factory('App\Reply')->create();
        $updateReply = factory('App\Reply')->make();
        $this->json('patch', "replies/{$reply->id}", [
            'body' => $updateReply->body
        ])
            ->assertStatus(403);;
    }

    /** @test */
    public function authorized_users_can_update_their_replies()
    {
        $this->be(factory('App\User')->create());
        $reply = factory('App\Reply')->create(['user_id' => auth()->id()]);
        $updateReply = factory('App\Reply')->make();
        $this->json('patch', "replies/{$reply->id}", [
            'body' => $updateReply->body
        ]);

        $this
            ->assertDatabaseMissing('replies', ['id' => $reply->id, 'body' => $reply->body])
            ->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updateReply->body]);
    }

    /** @test */
    public function it_can_know_if_is_created_just_a_minute_ago()
    {
        $reply = factory('App\Reply')->create();

        $this->assertTrue($reply->wasAddedRecently());
        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasAddedRecently());
    }

    /** @test */
    public function it_can_detect_its_mentioned_users()
    {
        $this->withoutExceptionHandling();
        $reply = new \App\Reply([
            'body' => 'user @adnan was mentioned and either @behzad too.'
        ]);

        $this->assertEquals(['adnan', 'behzad'], $reply->mentionedUsers());
    }

    /** @test */
    public function it_makes_mentioned_users_surrounded_by_anchor()
    {
        $this->withoutExceptionHandling();
        $reply = new \App\Reply([
            'body' => 'user @adnan.'
        ]);

        $this->assertEquals(
            'user <a href="/profiles/adnan">@adnan</a>.',
            $reply->body
        );
    }

    /** @test */
    public function it_can_know_if_is_best_reply_or_not()
    {
        $this->be(factory('App\User')->create());
        $reply = factory('App\Reply')->create();

        $this->assertFalse($reply->isBest());

        $reply->thread->bestReply($reply);

        $this->assertTrue($reply->isBest());
    }
}
