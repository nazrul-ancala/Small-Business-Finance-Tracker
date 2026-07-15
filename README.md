# BFCS / DuitBuku

Small business finance tracker. Malay name "DuitBuku" = money book. Tracks transactions, invoices, customers, cashflow forecast, owner drawings, and P&L reports.

<!-- Add a screenshot once available: ![Dashboard](docs/screenshot.png) -->
<img width="1887" height="908" alt="image" src="https://github.com/user-attachments/assets/9b3985d2-0627-4188-a80d-4286b50e39a8" />

<img width="1896" height="910" alt="image" src="https://github.com/user-attachments/assets/d5b20c5c-540b-4c17-b7f9-5b04a734de0a" />

<img width="1900" height="912" alt="image" src="https://github.com/user-attachments/assets/93c51b8e-e928-4a64-b44b-6e8eef8ea655" />

<img width="1888" height="910" alt="image" src="https://github.com/user-attachments/assets/03dacf92-7d77-43f3-91f8-a814b11295c2" />

<img width="1887" height="912" alt="image" src="https://github.com/user-attachments/assets/d8c6c92c-cd48-46ed-85dd-b9ba636f1f83" />


## Features

- Transaction ledger — income/expense, categorized
- Invoices — line items, linked payment records, payment status
- Customers — contact/client management for invoicing
- Cashflow forecast — separate from actual transactions, for planning ahead
- Recurring entries — templates that spawn transactions automatically
- Owner drawings — cash/bank/salary/goods withdrawals tracked separately from business expense
- P&L reports — computed on demand from underlying ledger data

## Highlights

What this project demonstrates:

- **Service-oriented architecture** — web app and API are two independently deployable Laravel services, not a monolith, communicating over authenticated HTTP.
- **Service-to-service auth** — stateless Basic Auth (shared secret) between web and API, separate from the session-based auth used for human logins.
- **Stored-procedure-driven API** — business logic (validation, save/update/delete branching) lives in MySQL stored procedures, not scattered across PHP controllers; controllers stay thin, single-purpose wrappers.
- **Multi-container Docker setup** — 6 services (2× php-fpm, 2× nginx, MySQL, a scheduler worker) orchestrated via `docker-compose.yml`, each app fully isolated with its own vendor volume.
- **Clear layering** — web controllers never touch the database directly; all reads/writes for business data are proxied through the API layer.

## Architecture

Two separate Laravel apps + a shared MySQL database.

```
Browser
  │
  ▼
DuitBuku (web app, :8080)
  Blade views + session auth
  Controllers hold NO business logic — just call the API
  │  HTTP (Basic Auth)
  ▼
DuitBuku-api (API app, :8081)
  Endpoints call MySQL stored procedures
  │
  ▼
MySQL 8 — db `bfcs_db`, shared by both apps
```

DuitBuku (web) never touches the DB directly — it calls DuitBuku-api over HTTP for everything. Both apps + MySQL are wired together via the root [docker-compose.yml](docker-compose.yml).

## Tech stack

Laravel 12 / PHP 8.2 (both apps) · Tailwind 4 + Vite (web, no JS framework) · MySQL 8 · Docker Compose + nginx

## Repo layout

| Path | What |
|---|---|
| `DuitBuku/` | Web app |
| `DuitBuku-api/` | API app |
| `docker/nginx/` | nginx reverse-proxy configs |
| `docker-compose.yml` | Orchestrates both apps + shared MySQL |
