<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_delete_its_own_thread()
    {
        list($userThread,) = $this->createThreads();

        $this->deleteThread($userThread);

        $this
            ->assertDatabaseMissing('threads', ['id' => $userThread->id])
        ;
    }

    /** @test */
    public function a_user_that_does_not_own_a_thread_can_not_delete_the_thread()
    {
        list($userThread, $thread) = $this->createThreads();

        $this
            ->withExceptionHandling()
            ->delete($thread->path())
            ->assertStatus(403);
        $this
            ->assertDatabaseHas('threads', ['id' => $thread->id])
        ;
    }

    /** @test */
    public function with_deleting_a_thread_its_replies_will_be_deleted_along()
    {
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);
        $reply = factory('App\Reply')->create(['thread_id' => $thread->id]);

        $this->json('delete', $thread->path());
        $this
            ->assertDatabaseMissing('threads', ['id' => $thread->id])
            ->assertDatabaseMissing('replies', ['id' => $reply->id])
        ;
    }

    /** @test */
    public function a_user_can_delete_its_thread_by_json_request()
    {
        list($userTreat, $thread) = $this->createThreads();

        $this->json('delete', $userTreat->path())
            ->assertStatus(204)
        ;

        $this
            ->assertDatabaseMissing('threads', ['id' => $userTreat->id])
        ;
    }

    /** @test */
    public function a_user_can_not_a_thread_that_is_not_its_thread_by_json_request()
    {
        list($userThread, $thread) = $this->createThreads();

        $this
            ->withExceptionHandling()
            ->json('delete', $thread->path())
            ->assertStatus(403)
        ;

        $this
            ->assertDatabaseHas('threads', ['id' => $thread->id]);
        ;
    }

    /** @test */
    public function guest_can_not_delete_a_thread_by_json_request()
    {
        $this->withoutExceptionHandling();
        $this->expectException('\Illuminate\Auth\AuthenticationException');
        $this->json('delete', "/threads/channel/1");
    }

    /** @test */
    public function guest_can_not_delete_a_thread()
    {
        $this->withoutExceptionHandling();
        $this->expectException('\Illuminate\Auth\AuthenticationException');
        $this->delete("/threads/channel/1");
    }

    /**
     * @return array
     */
    protected function createThreads(): array
    {
        $this->be(factory('App\User')->create(['name' => 'some_user']));

        $userThread = factory('App\Thread')->create(['user_id' => auth()->id()]);
        $thread     = factory('App\Thread')->create();

        $this->withoutExceptionHandling();
        return [$userThread, $thread];
    }

    /**
     * @param $thread
     */
    protected function deleteThread($thread): void
    {
        $this->delete($thread->path());
    }
}
