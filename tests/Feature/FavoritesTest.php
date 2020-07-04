<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_can_not_favorite_replies()
    {
        $this->post('/replies/1/favorites')
            ->assertRedirect('/login')
        ;
    }

    /** @test */
    public function users_can_favorite_replies()
    {
        $this->be(factory('App\User')->create());
        $reply = factory('App\Reply')->create();

        $this->post("/replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function users_can_undo_favoriting_replies()
    {
        $this->be(factory('App\User')->create());
        $reply = factory('App\Reply')->create();

        $this->post("/replies/{$reply->id}/favorites");
        $this->json('delete', "/replies/{$reply->id}/favorites");

        $this->assertCount(0, $reply->favorites);
    }
}
