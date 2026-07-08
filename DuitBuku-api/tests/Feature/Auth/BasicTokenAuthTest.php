<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class BasicTokenAuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set known test credentials so tests are predictable
        config([
            'api.pass1' => 'testuser',
            'api.pass2' => 'testpass',
        ]);
    }

    private function validAuthHeader(): string
    {
        return 'Basic ' . base64_encode('testuser:testpass');
    }

    // --- Missing / malformed header ---

    public function test_missing_auth_header_returns_401(): void
    {
        $this->getJson('/api/Transaction/GET_TransactionList')
             ->assertStatus(401)
             ->assertJson(['Success' => false]);
    }

    public function test_non_basic_auth_scheme_returns_401(): void
    {
        $this->withHeaders(['Authorization' => 'Bearer sometoken'])
             ->getJson('/api/Transaction/GET_TransactionList')
             ->assertStatus(401);
    }

    // --- Wrong credentials ---

    public function test_wrong_username_returns_401(): void
    {
        $token = 'Basic ' . base64_encode('baduser:testpass');

        $this->withHeaders(['Authorization' => $token])
             ->getJson('/api/Transaction/GET_TransactionList')
             ->assertStatus(401)
             ->assertJson(['Success' => false, 'Message' => 'Invalid credentials.']);
    }

    public function test_wrong_password_returns_401(): void
    {
        $token = 'Basic ' . base64_encode('testuser:badpass');

        $this->withHeaders(['Authorization' => $token])
             ->getJson('/api/Transaction/GET_TransactionList')
             ->assertStatus(401);
    }

    // --- Valid credentials ---

    public function test_valid_token_passes_auth_layer(): void
    {
        // Auth passes — controller then hits DB which fails in test env (no stored procedures).
        // Assert 500 (not 401) to confirm the middleware let the request through.
        $response = $this->withHeaders(['Authorization' => $this->validAuthHeader()])
                         ->getJson('/api/Transaction/GET_TransactionList');

        $this->assertNotEquals(401, $response->status(), 'Expected auth to pass (status should not be 401)');
    }

    // --- Health endpoint (no auth required) ---

    public function test_health_endpoint_is_public(): void
    {
        $this->getJson('/api/health')
             ->assertStatus(200)
             ->assertJson(['Success' => true]);
    }
}
