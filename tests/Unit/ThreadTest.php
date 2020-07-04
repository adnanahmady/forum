<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;
    protected $mainPath = '/threads';

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->be(factory('App\User')->create());

        $this->thread->addReply(
            [
                'user_id' => auth()->id(),
                'body' => $this->thread->body
            ]
        );

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf('App\Channel',
            factory('App\Thread')->create()->channel
        );
    }

    /** @test */
    public function it_has_path()
    {
        $this->assertEquals(
            "{$this->mainPath}/{$this->thread->channel->slug}/{$this->thread->slug}",
            $this->thread->path()
        );
    }

    /** @test */
    public function a_thread_sends_notification_to_its_subscribers_when_adds_a_new_reply()
    {
        Notification::fake();

        $this
            ->be(factory('App\User')->create())
            ->thread
            ->subscribe()
            ->addReply([
                'user_id' => factory('App\User')->create()->id,
                'body' => 'some reply'
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function thread_can_identify_users_read_all_replies()
    {
        $this->be(factory('App\User')->create());

        $this->assertTrue($this->thread->hasNewUpdate());

        auth()->user()->read($this->thread);

        $this->assertFalse($this->thread->hasNewUpdate());
    }

    /** @test */
    public function users_can_create_a_reply_every_one_minute()
    {
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some reply'
        ]);

        $this->assertTrue(auth()->user()->latestReply->wasAddedRecently());
    }

    /** @test */
    public function replies_that_contains_spam_may_not_be_created()
    {
        $this->withoutExceptionHandling();

        $this->be(factory('App\User')->create());

        $this->expectException(\Exception::class);

        $reply = factory('App\Reply')->raw([
            'body' => 'invalid body'
        ]);

        $this->post($this->thread->path('replies'), $reply);
    }

    /** @test */
    public function a_thread_can_get_locked()
    {
        $this->withoutExceptionHandling();
        $this->assertNull($this->thread->locked);

        $this->thread->setLock();

        $this->assertNotNull($this->thread->locked);
    }
}
