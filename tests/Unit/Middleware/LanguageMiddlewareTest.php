<?php

namespace Tests\Unit\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class LanguageMiddlewareTest extends TestCase
{
    public function test_sets_locale_when_header_is_present()
    {
        $response = $this->withHeaders([
            'X-Language' => 'es',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-API-Version' => '1',
        ])->get('/api/admin/login');

        $response->assertJson(['locale' => 'es']);
    }

    public function test_does_not_change_locale_when_header_is_missing()
    {
        App::setLocale('en');

        $response = $this->get('/api/admin/login');

        $this->assertEquals('en', App::getLocale());
    }
}
