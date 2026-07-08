# BFCS / DuitBuku — System Design

## What it is
Small business finance tracker. Malay name "DuitBuku" = money book. Track transactions, invoices, customers, cashflow forecast, owner drawings, P&L reports.

## Big picture — 2 separate Laravel apps + shared MySQL DB

```
Browser
  │
  ▼
[DuitBuku]  (web app, port 8080)
  Blade views + session auth (web guard)
  Controllers hold NO business logic — just call the API
  │  HTTP (Basic Auth) via Http::withBasicAuth()
  ▼
[DuitBuku-api]  (API app, port 8081)
  Controllers under Manage/, guarded by 'basic.token' middleware
  Each endpoint = raw PDO CALL to a MySQL stored procedure
  │
  ▼
[MySQL 8] — db `bfcs_db`, shared by both apps
```

Both apps live in same repo, own composer/npm/vendor, own Dockerfile. Wired together by root [docker-compose.yml](../../docker-compose.yml) — nginx reverse proxy each (`app.conf` / `api.conf`), php-fpm per app, one shared `duitbuku-db` MySQL container, one Vite dev server per app.

Key point: **DuitBuku (web) never touches the DB directly.** Every controller action calls `$this->callApi(...)` (base [Controller.php](../../DuitBuku/app/Http/Controllers/Controller.php)) which does an authenticated HTTP call to DuitBuku-api. DuitBuku-api is the only thing with DB credentials for business tables.

## Auth — two different mechanisms, don't confuse them
- **DuitBuku (web):** normal Laravel session auth (`auth` middleware, `LoginController`), cookie-based, for humans in browser.
- **DuitBuku-api:** stateless Basic Auth via [BasicTokenAuth.php](../../DuitBuku-api/app/Http/Middleware/BasicTokenAuth.php) middleware `basic.token`. Compares against `config('api.pass1')`/`pass2` (env `TOKEN_PASS1`/`TOKEN_PASS2`). Not real per-user auth — it's a shared secret between the two apps (service-to-service).

## API design pattern
Routes in [routes/api.php](../../DuitBuku-api/routes/api.php) follow fixed convention, not REST:
- `GET  /{Resource}/GET_{Resource}List`
- `GET  /{Resource}/GET_Specific{Resource}`
- `POST /{Resource}/POST_{Resource}_SaveUpdateDelete` — one endpoint, `action` param picks save/update/delete

Resources: Transaction, Category, RecurringEntry, Customer, Invoice, PaymentRecord, Drawing, CashflowEntry, PL (reports only, no CRUD).

Response envelope always: `{ Success, Message, Data }`. See base API [Controller.php](../../DuitBuku-api/app/Http/Controllers/Controller.php) `ok()`/`fail()` helpers.

## Business logic lives in MySQL, not PHP
Every API controller method is a thin wrapper: grab request params → `CALL sp_X_CRUD(?,?,...)` → return rows. Actual validation/branching (save vs update vs delete, GET_ALL vs GET_BY_ID) happens inside the stored procedure. See [database/stored_procedures/](../../DuitBuku-api/database/stored_procedures/) — one `sp_*_CRUD` proc per resource, plus `sp_PL_Report.sql` for reports. Table shapes documented separately in [database/schema/*.sql](../../DuitBuku-api/database/schema/) (note: inconsistent style — some plain `CREATE TABLE IF NOT EXISTS`, some `CREATE TABLE` with engine/charset/index clauses — looks like schema files added at different times, not from one migration tool).

This is unusual for Laravel — no Eloquent models for business tables (only `User` model exists), no migrations for these tables either (migrations dir only has users/cache/jobs — schema/ dir is the real source of truth, applied manually).

## Domain model (from schema/)
- `customers` — client contacts for invoicing
- `invoices` (1) → `invoice_items` (N) → `payment_records` (N, against invoice_id)
- `transactions` — income/expense ledger, flat `category` varchar (not FK'd to `categories` table — inconsistent with cashflow_entries which does use `category_id`)
- `categories` — income/expense tagging, has color/icon for UI
- `recurring_entries` — templates that spawn transactions (web side has `/transactions/recurring/apply`)
- `cashflow_entries` — forecast bills/income, separate from actual `transactions`
- `drawings` — owner withdrawals (cash/bank/salary/goods)
- P&L — computed report, no own table, driven by `sp_PL_Report`

All rows default `user_id = 1` — **multi-tenancy not really implemented yet**, single-business assumption baked into schema defaults.

## Web app structure
Feature-folder controllers under `app/Http/Controllers/{feature}/`: auth, dashboard, transactions, invoices, drawings, cashflow, pl, business, account, landing, utilities. Each maps 1:1 to a Blade view under matching `resources/views/{feature}/`. Tailwind 4 + Vite, no JS framework (checked `package.json` — just tailwind/vite/axios).

## Things to know before touching this
- Change a stored procedure signature → must update every controller method that does `CALL sp_X_CRUD(?,?,...?)` with matching param count/order. No ORM to catch mismatches — fails at runtime.
- `schema/*.sql` is not applied automatically by `migrate` — no migration wraps these. Confirm how `bfcs_db` actually gets provisioned before assuming schema/ is live.
- Adding a new business table needs: schema/*.sql + stored procedure + API controller + route + (if web needs it) web controller + Blade view. Four-plus layers for one feature, by design of this project.
- `TOKEN_PASS1`/`TOKEN_PASS2` shared secret — same value must exist in both apps' `.env` for web→API calls to succeed.
