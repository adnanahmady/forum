<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_validates_spam()
    {
        $spam = new \App\Custom\Spam();

        $this->assertFalse($spam->detect('innocent reply body'));
    }

    /** @test */
    public function it_detects_invalid_body()
    {
        $spam = new \App\Custom\Spam();

        $this->expectException(\Exception::class);

        $spam->detect('inesent reply body invalid');
    }

    /** @test */
    public function it_detects_repeated_letters()
    {
        $spam = new \App\Custom\Spam();

        $this->expectException(\Exception::class);

        $spam->detect('some texttttttttttt');
    }
}
