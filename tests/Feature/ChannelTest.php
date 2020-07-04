<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function threads_have_to_be_filtered_by_channel_if_channel_is_presented()
    {
        $channel = factory('App\Channel')->create();
        $threadWithChannel = factory('App\Thread')->create(['channel_id' => $channel->id]);
        $threadWithoutChannel = factory('App\Thread')->create();

        $this->get("threads/{$channel->slug}")
            ->assertSee($threadWithChannel->title)
            ->assertDontSee($threadWithoutChannel->title);
    }
}
