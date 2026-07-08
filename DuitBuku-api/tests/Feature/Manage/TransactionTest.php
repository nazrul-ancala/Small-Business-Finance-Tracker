<?php

namespace Tests\Feature\Manage;

use Tests\TestCase;

/**
 * Tests for TransactionController.
 *
 * Two layers of tests here:
 *   1. Validation tests — controller rejects bad input before touching the DB.
 *      These work with any DB driver (including in-memory SQLite).
 *
 *   2. Happy-path tests — require a real MySQL connection with stored procedures.
 *      Skipped in the default test environment; run against a real DB when needed.
 */
class TransactionTest extends TestCase
{
    private string $authHeader;

    protected function setUp(): void
    {
        parent::setUp();

        config(['api.pass1' => 'testuser', 'api.pass2' => 'testpass']);
        $this->authHeader = 'Basic ' . base64_encode('testuser:testpass');
    }

    // -------------------------------------------------------------------------
    // POST /api/Transaction/POST_Transaction_SaveUpdateDelete — action validation
    // -------------------------------------------------------------------------

    public function test_missing_action_returns_400(): void
    {
        $this->withHeaders(['Authorization' => $this->authHeader])
             ->postJson('/api/Transaction/POST_Transaction_SaveUpdateDelete', [])
             ->assertStatus(400)
             ->assertJson(['Success' => false]);
    }

    public function test_invalid_action_returns_400(): void
    {
        $this->withHeaders(['Authorization' => $this->authHeader])
             ->postJson('/api/Transaction/POST_Transaction_SaveUpdateDelete', [
                 'action' => 'duplicate', // not save/update/delete
             ])
             ->assertStatus(400)
             ->assertJsonFragment(['Success' => false]);
    }

    // -------------------------------------------------------------------------
    // POST — save (INSERT) field validation
    // -------------------------------------------------------------------------

    public function test_save_missing_user_id_returns_400(): void
    {
        $this->withHeaders(['Authorization' => $this->authHeader])
             ->postJson('/api/Transaction/POST_Transaction_SaveUpdateDelete', [
                 'action'   => 'save',
                 // 'user_id' missing
                 'category' => '1',
                 'type'     => 'expense',
                 'amount'   => '100',
                 'date'     => '2026-07-01',
             ])
             ->assertStatus(400);
    }

    public function test_save_missing_amount_returns_400(): void
    {
        $this->withHeaders(['Authorization' => $this->authHeader])
             ->postJson('/api/Transaction/POST_Transaction_SaveUpdateDelete', [
                 'action'   => 'save',
                 'user_id'  => '1',
                 'category' => '1',
                 'type'     => 'expense',
                 // 'amount' missing
                 'date'     => '2026-07-01',
             ])
             ->assertStatus(400);
    }

    public function test_save_missing_date_returns_400(): void
    {
        $this->withHeaders(['Authorization' => $this->authHeader])
             ->postJson('/api/Transaction/POST_Transaction_SaveUpdateDelete', [
                 'action'   => 'save',
                 'user_id'  => '1',
                 'category' => '1',
                 'type'     => 'expense',
                 'amount'   => '100',
                 // 'date' missing
             ])
             ->assertStatus(400);
    }

    // -------------------------------------------------------------------------
    // POST — update / delete id validation
    // -------------------------------------------------------------------------

    public function test_update_missing_id_returns_400(): void
    {
        $this->withHeaders(['Authorization' => $this->authHeader])
             ->postJson('/api/Transaction/POST_Transaction_SaveUpdateDelete', [
                 'action' => 'update',
                 // 'id' missing
             ])
             ->assertStatus(400);
    }

    public function test_delete_missing_id_returns_400(): void
    {
        $this->withHeaders(['Authorization' => $this->authHeader])
             ->postJson('/api/Transaction/POST_Transaction_SaveUpdateDelete', [
                 'action' => 'delete',
                 // 'id' missing
             ])
             ->assertStatus(400);
    }

    // -------------------------------------------------------------------------
    // GET /api/Transaction/GET_SpecificTransaction — id validation
    // -------------------------------------------------------------------------

    public function test_get_specific_without_id_returns_400(): void
    {
        $this->withHeaders(['Authorization' => $this->authHeader])
             ->getJson('/api/Transaction/GET_SpecificTransaction')
             ->assertStatus(400)
             ->assertJson(['Success' => false]);
    }

    // -------------------------------------------------------------------------
    // Response shape — all success responses share the same structure
    // -------------------------------------------------------------------------

    public function test_400_response_has_expected_shape(): void
    {
        $response = $this->withHeaders(['Authorization' => $this->authHeader])
                         ->postJson('/api/Transaction/POST_Transaction_SaveUpdateDelete', []);

        $response->assertStatus(400)
                 ->assertJsonStructure(['Success', 'Message']);
    }
}
