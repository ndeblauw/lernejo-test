<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DomainTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        \App\Models\Tenant::create([
            'name' => 'De Gazelle',
            'subdomain' => 'gazelle',
            'domain' => 'gazelle.test',
        ]);

        \App\Models\Tenant::create([
            'name' => 'Het Boszicht',
            'subdomain' => 'boszicht',
            'domain' => 'boszicht.test',
            'use_domain' => true,
        ]);
    }

    public function siteDataProvider()
    {
        /*
         * 1 => lernejo.test is the landlord domain
         * 2,3,4, => gazelle.test, boszicht.test, gazelle.lernejo.test are valid tenant domains
         * 5 => boszicht.lernejo.test is a valid tenant domain, but should be redirected to boszicht.test
         * 6 => toverbol.lernejo.test is an invalid tenant domain
        */

        return [
            1 => ['url' => 'http://lernejo.test', 'status' => 200, 'redirect' => null, 'tenant_id' => null],
            2 => ['url' => 'http://gazelle.test', 'status' => 200, 'redirect' => null, 'tenant_id' => 1],
            3 => ['url' => 'http://boszicht.test', 'status' => 200, 'redirect' => null, 'tenant_id' => 2],
            4 => ['url' => 'http://gazelle.lernejo.test', 'status' => 200, 'redirect' => null, 'tenant_id' => 1],
            5 => ['url' => 'http://boszicht.lernejo.test', 'status' => 302, 'redirect' => 'http://boszicht.test', 'tenant_id' => 2],
            6 => ['url' => 'http://toverbol.lernejo.test', 'status' => 500, 'redirect' => null, 'tenant_id' => null],
        ];
    }

    /** @test @dataProvider siteDataProvider */
    public function check_if_domains_can_be_reached($url, $status, $redirect, $tenant_id)
    {
        echo $url;
        $response = $this->get($url);

        // Basic checks
        $response->assertStatus($status);
        $response->assertSeeText('Url: '.$url);

        // Happy paths
        if ($status == 200 && $tenant_id !== null) {

            $response->assertSessionHas('tenant_id', $tenant_id);
            $response->assertSeeText('Tennant id: '.$tenant_id);
        }

        // Happy path With a redirect
        if ($status == 302) {
            $response->assertRedirect($redirect);
            $response->assertSessionMissing('tenant_id');

            $this->followingRedirects();
            $response = $this->get($url);
            $response->assertSessionHas('tenant_id', $tenant_id);
        }
    }
}
