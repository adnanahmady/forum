<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReputationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_earns_points_when_they_create_a_thread()
    {
        $thread = factory('App\Thread')->create();

        $this->assertEquals(10, $thread->creator->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_they_reply_to_a_thread()
    {
        $thread = factory('App\Thread')->create();

        $reply = $thread->addReply([
            'user_id' => factory('App\User')->create()->id,
            'body' => 'here is a reply.'
        ]);

        $this->assertEquals(2, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_marked_as_best()
    {
        $thread = factory('App\Thread')->create();

        $reply = $thread->addReply([
            'user_id' => factory('App\User')->create()->id,
            'body' => 'here is a reply.'
        ]);
        $thread->bestReply($reply);

        $this->assertEquals(52, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_liked()
    {
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $reply = $thread->addReply([
            'user_id' => factory('App\User')->create()->id,
            'body' => 'here is a reply.'
        ]);
        $reply->favorited();

        $this->assertEquals(6, $reply->owner->reputation);
    }
}
