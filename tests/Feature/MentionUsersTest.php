<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function users_can_mention_other_users_in_reply()
    {
        $this->withoutExceptionHandling();
        $adnan = factory('App\User')->create(['name' => 'adnan']);
        $erfan = factory('App\User')->create(['name' => 'erfan']);
        $this->be($adnan);

        $thread = factory('App\Thread')->create(['user_id' => $adnan->id]);

        $this->post($thread->path('replies'), ['body' => 'mention @erfan in this reply. @behzad yoo']);

        $this->assertCount(1, $erfan->fresh()->notifications);
    }

    /** @test */
    public function it_returns_mentioned_users()
    {
        $this->withoutExceptionHandling();
        factory('App\User')->create(['name' => 'adnan']);
        factory('App\User')->create(['name' => 'adnan2']);
        factory('App\User')->create(['name' => 'erfan']);
        $result = $this->json('get', '/api/users', ['search' => 'adn']);

        $this->assertCount(2, $result->json());
    }
}
