<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Debug\ExceptionHandler;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct(){}

            public function report(\Exception $e) {}

            public function render($request, \Exception $exception)
            {
                throw $exception;
            }
        });

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->disableForeignKeys();
    }

    /**
     * Disable foreign keys.
     *
     * @return void
     */
    public function disableForeignKeys()
    {
        $db = app()->make('db');
        $db->getSchemaBuilder()->disableForeignKeyConstraints();
    }

    /**
     * Enables foreign keys.
     *
     * @return void
     */
    public function enableForeignKeys()
    {
        $db = app()->make('db');
        $db->getSchemaBuilder()->enableForeignKeyConstraints();
    }
}
