<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $newTitle;
    protected $newBody;
    protected $thread;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->newTitle = 'updated title';
        $this->newBody = 'updated body';
        $this->user = factory('App\User')->create();
        $this->thread = factory('App\Thread')->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_be_updated_by_authorized_users()
    {
        $this->withoutExceptionHandling();
        $this->be($this->user);

        $this->patchJson($this->thread->path(), [
            'title' => $this->newTitle,
            'body' => $this->newBody
        ])->assertStatus(200);

        $thread = $this->thread->fresh();
        $this->assertEquals($this->newTitle, $thread->title);
        $this->assertEquals($this->newBody, $thread->body);
    }

    /** @test */
    public function it_can_not_be_updated_by_guests()
    {
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->patchJson($this->thread->path());
    }

    /** @test */
    public function it_can_not_be_updated_by_unauthorized_users()
    {
//        $this->withoutExceptionHandling();
        $this->be($this->user);
        $thread = factory('App\Thread')->create();

        $this->patchJson($thread->path(), [
            'title' => $this->newTitle,
            'body' => $this->newBody
        ])->assertStatus(403);

        $thread = $thread->fresh();
        $this->assertNotEquals($this->newTitle, $thread->title);
        $this->assertNotEquals($this->newBody, $thread->body);


    }

    /** @test */
    public function it_requires_title_and_body_for_updating_threads()
    {
        $this->be($this->user);

        $this->patch($this->thread->path(), [
            'title' => $this->newTitle
        ])->assertSessionHasErrors('body');

        $this->patch($this->thread->path(), [
            'body' => $this->newBody
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function after_updating_process_it_returns_new_thread()
    {
        $this->be($this->user);
        $response = $this->patch($this->thread->path(), [
            'title' => $this->newTitle,
            'body' => $this->newBody,
        ]);
        $thread = $this->thread->fresh();
        $this->assertEquals($thread->title, $response->json()['title']);
    }

    /** @test */
    public function it_can_only_be_updated_by_its_creator()
    {
        $this->be(factory('App\User')->create());
        $this->patch($this->thread->path())->assertStatus(403);
    }
}
