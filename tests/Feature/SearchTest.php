<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_search_threads()
    {
        $search = 'search text 5';
        config(['scout.driver' => 'algolia']);

        factory('App\Thread', 2)->create();
        factory('App\Thread', 2)->create(['body' => "a thread body containing $search text"]);

        do {
            sleep(.25);

            $result = $this->getJson("/threads/search?s=$search")->json()['data'];
        } while (count($result) < 2);

        $this->assertCount(2, $result);

        Thread::get()->unsearchable();
    }
}
