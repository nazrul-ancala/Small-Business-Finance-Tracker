# Request Flow — Save a Transaction (worked example)

Concrete walkthrough, one action end to end. Same shape for every other resource (Invoice, Customer, Drawing, etc) — swap names.

```
Browser (form submit, POST /transactions/save)
        │
        ▼
DuitBuku  manageAllTransactionsController::save()
  [DuitBuku/app/Http/Controllers/transactions/manageAllTransactionsController.php:31]
  - reads form input (date, amount, category, type, note)
  - normalizes date DD/MM/YYYY → YYYY-MM-DD
  - calls $this->callApi('POST', 'Transaction/POST_Transaction_SaveUpdateDelete', [...])
        │
        ▼
Controller::callApi()  [DuitBuku/app/Http/Controllers/Controller.php:9]
  - Http::withBasicAuth(pass1, pass2)->asForm()->post(
        "{API_URL}/api/Transaction/POST_Transaction_SaveUpdateDelete", $params)
  - this is the ONLY link between the two apps — plain HTTP, not shared PHP code
        │
        ▼  (network hop, nginx duitbuku-api-web → php-fpm duitbuku-api-php)
        │
DuitBuku-api  routes/api.php:21
  - middleware('basic.token') → BasicTokenAuth::handle()
    [DuitBuku-api/app/Http/Middleware/BasicTokenAuth.php:12]
    - decodes Authorization: Basic header
    - compares against config('api.pass1')/pass2 (env TOKEN_PASS1/TOKEN_PASS2)
    - 401 json if mismatch, else next()
        │
        ▼
TransactionController::POST_Transaction_SaveUpdateDelete()
  [DuitBuku-api/app/Http/Controllers/Manage/TransactionController.php:56]
  - reads 'action' param → 'save' → saveTransaction()
  - saveTransaction() validates required fields present
  - callCrud('INSERT', [null, user_id, category, type, amount, note, date, null, null])
    [TransactionController.php:140]
        │
        ▼
Raw PDO call  DB::connection()->getPdo()->prepare('CALL sp_Transaction_CRUD(?,?,?,?,?,?,?,?,?,?)')
  - no Eloquent, no query builder — straight stored-procedure call
        │
        ▼
MySQL  sp_Transaction_CRUD
  [DuitBuku-api/database/stored_procedures/sp_Transaction_CRUD.sql]
  - branches on first param ('INSERT' / 'UPDATE' / 'DELETE' / 'GET_ALL' / 'GET_BY_ID')
  - does the actual INSERT into `transactions` table
  - returns a row: Status ('true'/'false'), Message, NewId
        │
        ▼ (bubbles back up)
TransactionController: row.Status === 'true' → $this->ok(['id' => NewId], row.Message)
  → JSON { Success: true, Message, Data } to DuitBuku-api response
        │
        ▼
Controller::callApi() returns decoded json to manageAllTransactionsController::save()
        │
        ▼
manageAllTransactionsController::save() → response()->json($response) straight back to browser
```

## Read flow (list page) — same shape, GET instead of POST
`GET /transactions` → `manageAllTransactionsController::index()` → `callApi('GET', 'Transaction/GET_TransactionList', ['month', 'category'])` → API `TransactionController::GET_TransactionList()` → `CALL sp_Transaction_CRUD('GET_ALL', ...)` → rows → `ok($rows)` → web wraps rows as objects → `view('transactions.all_transactions', ...)` renders Blade.

## Rules that fall out of this flow
- **Every write and read crosses HTTP**, even though both apps sit on the same box. No shortcut, no shared DB access from the web app — intentional isolation, but means API being down = web app fully broken (no local fallback).
- **Validation lives in 3 places**: web controller (light, e.g. date format), API controller (`required field` checks before calling proc), stored procedure (business rules, actual constraint enforcement). Changing behavior may require touching 2-3 layers.
- **Errors surface as `Status`/`Message` string fields from the proc**, not exceptions — `if ($row && $row->Status === 'true')` is the success check everywhere. A busted stored proc that returns no row silently becomes a generic "Failed to X" 400, not a 500 — worth checking logs (`Log::error(...)` in each catch block) when this happens.
- **Basic-token secret is transport-level auth between apps**, not user identity — API has no concept of "which user" beyond whatever `user_id` the web app happens to pass in the POST body (currently hardcoded `1` everywhere, see `manageAllTransactionsController::save():47`).
