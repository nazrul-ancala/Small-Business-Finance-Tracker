<?php

namespace Tests\Feature\Transactions;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Tests the DuitBuku frontend transaction routes.
 *
 * Http::fake() intercepts all outbound HTTP calls made by callApi(),
 * so these tests run without a live DuitBuku-api server.
 */
class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);

        // Point the API URL at a fake host so Http::fake patterns match cleanly
        config(['api.url' => 'http://fake-api']);
    }

    private function actingAsOwner(): static
    {
        return $this->actingAs(User::first());
    }

    // --- GET /transactions ---

    public function test_transactions_page_loads_and_passes_data_to_view(): void
    {
        // Trailing * matches URLs with query params (e.g. ?month=2026-07)
        Http::fake([
            'http://fake-api/api/Transaction/*' => Http::response([
                'Success' => true,
                'Data'    => [
                    ['id' => 1, 'type' => 'expense', 'amount' => 100.00, 'category' => 'Food', 'date' => '2026-07-01', 'note' => ''],
                    ['id' => 2, 'type' => 'income',  'amount' => 500.00, 'category' => 'Salary', 'date' => '2026-07-01', 'note' => ''],
                ],
            ]),
            'http://fake-api/api/Category/*' => Http::response([
                'Success' => true,
                'Data'    => [
                    ['id' => 1, 'name' => 'Food',   'type' => 'expense'],
                    ['id' => 2, 'name' => 'Salary', 'type' => 'income'],
                ],
            ]),
        ]);

        $this->actingAsOwner()
             ->get('/transactions')
             ->assertStatus(200)
             ->assertViewHas('transactions')
             ->assertViewHas('incomeCats')
             ->assertViewHas('expenseCats');
    }

    public function test_transactions_page_requires_auth(): void
    {
        $this->get('/transactions')->assertRedirect('/login');
    }

    // --- POST /transactions/save ---

    public function test_save_transaction_proxies_api_response(): void
    {
        Http::fake([
            'http://fake-api/api/Transaction/*' => Http::response([
                'Success' => true,
                'Message' => 'Transaction saved.',
                'Data'    => ['id' => 99],
            ]),
        ]);

        $this->actingAsOwner()
             ->post('/transactions/save', [
                 'category' => '1',
                 'type'     => 'expense',
                 'amount'   => '50.00',
                 'date'     => '01/07/2026',
                 'note'     => 'Lunch',
             ])
             ->assertStatus(200)
             ->assertJson(['Success' => true]);
    }

    public function test_save_converts_date_format_before_calling_api(): void
    {
        Http::fake([
            'http://fake-api/api/Transaction/*' => Http::response([
                'Success' => true, 'Message' => 'Saved.', 'Data' => [],
            ]),
        ]);

        $this->actingAsOwner()
             ->post('/transactions/save', [
                 'category' => '1',
                 'type'     => 'expense',
                 'amount'   => '50.00',
                 'date'     => '15/07/2026', // DD/MM/YYYY from front-end datepicker
             ]);

        // Verify the API received YYYY-MM-DD format
        Http::assertSent(function ($request) {
            return $request->data()['date'] === '2026-07-15';
        });
    }

    // --- POST /transactions/delete ---

    public function test_delete_transaction_proxies_api_response(): void
    {
        Http::fake([
            'http://fake-api/api/Transaction/*' => Http::response([
                'Success' => true,
                'Message' => 'Transaction deleted.',
            ]),
        ]);

        $this->actingAsOwner()
             ->post('/transactions/delete', ['id' => 1])
             ->assertStatus(200)
             ->assertJson(['Success' => true]);
    }
}
