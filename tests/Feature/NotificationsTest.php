<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->be(factory('App\User')->create());
    }

    /** @test */
    public function when_other_users_reply_to_a_users_subscribed_thread_a_notification_appears()
    {
        $this->withoutExceptionHandling();

        $thread = factory('App\Thread')->create();

        $this->assertCount(0, auth()->user()->notifications);

        $this->post($thread->path('subscribes'));

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some reply'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => factory('App\User')->create()->id,
            'body' => 'some new reply'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
    
    /** @test */
    public function a_user_can_mark_unread_notifications_as_read()
    {
        $this->withoutExceptionHandling();

        factory(DatabaseNotification::class)->create();

        tap(auth()->user(), function ($user) {
            $unreads = $user->unreadNotifications;

            $this->assertCount(1, $unreads);

            $this->delete("/profiles/{$user->name}/notifications/{$unreads->first()->id}");

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }

    /** @test */
    public function a_user_can_see_its_unread_notifications()
    {
        $this->withoutExceptionHandling();

        factory(DatabaseNotification::class)->create();

        $response = $this->getJson('/profiles/notifications')->json();

        $this->assertCount(1, $response);
    }
}
