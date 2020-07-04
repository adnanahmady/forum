<?php

namespace Tests\Unit;

use App\Rules\Recaptcha;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_can_not_add_a_thread()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_add_a_thread()
    {
        list($thread, $response) = $this->addThread();

        $this->get($response->headers->get('Location'))
            ->assertSee($thread['body'])
            ->assertSee($thread['title']);
    }

    /**
     * posts a thread for authenticated user
     *
     * @param array $thread
     * @param array $recaptcha
     *
     * @return array
     */
    protected function addThread($thread = [], $recaptcha = ['g-recaptcha-response' => 'test']): array
    {
        $this->actingAs(factory('App\User')->create());
        $thread = factory('App\Thread')->raw($thread);
        $response = $this->post('/threads', $thread + $recaptcha);

        return [$thread, $response];
    }

    /** @test */
    public function title_is_required_for_adding_thread()
    {
        list(, $response) = $this->addThread(
            [
                'title' => null
            ]
        );
        $response
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function body_is_required_for_adding_thread()
    {
        list(, $response) = $this->addThread(
            [
                'body' => null
            ]
        );
        $response
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function recaptcha_is_required_for_adding_thread()
    {
        list(, $response) = $this->addThread([], []);
        $response->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    public function recaptcha_must_be_valid()
    {
        unset($this->app[Recaptcha::class]);
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->raw();
        $this->post('/threads', $thread + ['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    public function channel_id_is_required_for_adding_thread()
    {
        $channels = factory('App\Channel', 2)->create();
        list(, $response) = $this->addThread(
            [
                'channel_id' => null
            ]
        );
        $response
            ->assertSessionHasErrors('channel_id');

        list(, $response) = $this->addThread(
            [
                'channel_id' => 999
            ]
        );
        $response
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function authenticated_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $this->be(factory('App\User')->state('unconfirmed')->create());
        $thread = factory('App\Thread')->raw();

        $this->post('/threads', $thread + ['g-recaptcha-response' => 'test'])
            ->assertRedirect('/threads')
            ->assertSessionHas('flash');
    }

    /** @test */
    public function a_user_can_choose_channel_for_new_thread()
    {
        $this->be(factory('App\User')->create());
        $channel = factory('App\Channel')->create();
        factory('App\Thread')->create();

        $this->get('/threads/create')
            ->assertSee(htmlentities($channel->name, ENT_QUOTES));
    }

    /** @test */
    public function it_requires_unique_slugs()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create(['title' => 'Foo bar']);

        $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'test']);

        $this->assertTrue(Thread::whereSlug('foo-bar-2')->exists());
        $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'test']);

        $this->assertTrue(Thread::whereSlug('foo-bar-3')->exists());
    }

    /** @test */
    public function it_handles_titles_containing_number_at_the_end_of()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create(['title' => 'Foo bar 22']);

        $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'test']);
        $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'test']);
        $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'test']);

        $this->assertTrue(Thread::whereSlug('foo-bar-22-2')->exists());
        $this->assertTrue(Thread::whereSlug('foo-bar-22-3')->exists());
        $this->assertTrue(Thread::whereSlug('foo-bar-22-4')->exists());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                return $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }
}
