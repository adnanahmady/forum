<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function everyone_can_see_threads_replies()
    {
        $this->withoutExceptionHandling();
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply', 4)->create(['thread_id' => $thread->id]);

        $response = $this->getJson($thread->path('replies'))->json();

        $this->assertContains('current_page', array_keys($response));
        $this->assertCount(4, $response['data']);
        $this->assertEquals(4, $response['total']);
    }

    /** @test */
    public function a_reply_body_is_sanitized_automatically()
    {
        $allowedElement = "<p>this is allowed</p>";
        $body = "<script>alert('alert injected')</script>$allowedElement";
        $reply = factory('App\Reply')->make(['body' => $body]);

        $this->assertEquals($reply->body, $allowedElement);
    }
}
