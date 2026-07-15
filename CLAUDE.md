# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

JobSwap.lv — an anonymous job-swap marketplace for Latvia (Laravel 13 + Livewire 3 + Breeze + Cashier/Stripe, SQLite in dev). Workers post anonymous swap offers, chat, and request swaps; both employers approve via tokenized email links (no employer accounts); on full confirmation each worker is charged €5 via Stripe manual-capture PaymentIntents. Default locale is Latvian (`lv`), with an EN toggle.

## Commands

```bash
npm run setup      # first-time: composer install, .env, key, migrate, npm install, build, seeds
npm start          # dev stack via concurrently: artisan serve + queue:listen + vite
php artisan test                                      # full suite (in-memory SQLite)
php artisan test --filter=test_name_or_ClassName      # single test
php vendor/bin/pint                                   # code style (Laravel Pint)
php artisan migrate:fresh --seed                      # reset DB (test worker + admin accounts)
php artisan db:seed --class=DemoSeeder                # 12 Latvian demo posts (idempotent)
npm run build                                         # production assets
```

Seeded logins (password `password`): `test@example.com` (worker), `admin@jobswap.lv` (admin, role column on users).

`npm start` deliberately does **not** use `composer run dev` — Laravel's stock dev script includes `pail`, which needs the `pcntl` extension that doesn't exist on Windows, and `--kill-others` then takes down the whole stack.

This repo is indexed with codegraph — prefer `codegraph explore "<question or symbols>"` (or the `codegraph_explore` MCP tool) over grep/read loops for symbol lookup, call flow, and impact questions.

## Architecture

**The swap state machine lives in one place: `app/Services/SwapFlowService.php`.** It is the only writer of `swap_requests.status` and all Stripe calls (manual-capture authorize on worker approval → capture both on second employer approval → cancel/release on any decline or failure). Every transition writes a `SwapActionLog` row. Statuses: `pending → awaiting_employers → confirmed`, with terminal `declined_by_worker | declined_by_employer | payment_failed | cancelled`. Posts flip to `swapped` only inside `confirmSwap()`. If you change flow behavior, change it here — Livewire components (`app/Livewire/Swaps/MySwaps.php`, `Chat/ConversationThread.php`) and `EmployerApprovalController` only call into this service.

**Anonymity model:** public identity is `users.handle` ("Worker #ABC123", generated in `User::booted()` — note `DatabaseSeeder` uses `WithoutModelEvents`, so the factory also sets `handle`). `posts.employer_email/employer_name` are `#[Hidden]` and must never appear in worker-facing views; real emails are revealed only in the swap-confirmed UI/mail. Employers interact solely through `employer_approvals.token` URLs (`/employer/respond/{token}`, no auth, link lifetime in `config/jobswap.php`).

**Laravel 13 `#[Fillable]` attribute gotcha (has bitten twice):** models use PHP-attribute fillable lists instead of `$fillable`. A `create()`/`update()` key missing from the list is **silently dropped** — no error, just a NOT NULL violation later or lost data. When adding a column that any `create()`/`update()` call sets, add it to that model's `#[Fillable]` first.

**Mail is queued:** all mailables implement `ShouldQueue`, so tests must use `Mail::assertQueued`, not `assertSent`. Dev queue runs inside `npm start` (`queue:listen`).

**Livewire structure:** full-page components under `app/Livewire/{Posts,Chat,Swaps,Payments,Admin}` declare `#[Layout('layouts.app')]` and render their own page container (there is no header slot in use). `Chat\ChatDock` is the Facebook-style bottom-right dock, mounted in `layouts/app.blade.php` inside `@persist('chat-dock')` so it survives `wire:navigate`; it polls every 7s. Shared UI lives in Blade components (`resources/views/components/`): buttons, inputs, `swap-progress` (the stepper — maps statuses to steps and renders per-employer chips), `empty-state`, `brand-logo`.

**Design tokens:** `tailwind.config.js` defines `brand` (carmine, Latvian-flag inspired) and `ink` colors plus `shadow-card*`. Use `zinc` neutrals in views (older admin views still use `gray` — acceptable). Both light and dark variants are maintained.

**Locale:** `SetLocale` middleware resolves session → user column → `config('app.locale')` (lv). UI strings go through `__()` with translations in `lang/lv.json`; framework strings in `lang/lv/*.php`. Every new user-facing string needs an `lv.json` entry. Dropdown options (regions, availability) and the swap fee live in `config/jobswap.php`, not the DB.

**Scheduled commands** (`routes/console.php`, daily): `app:expire-posts` and `app:send-expiry-reminders` (3 days before expiry, `expiry_reminder_sent_at` guards re-sends).

**Feature tests need built assets:** pages call `@vite`, so a missing `public/build/manifest.json` fails feature tests with 500s — run `npm run build` once first. A stale `public/hot` file (left by a killed Vite) makes the app load assets from a dead dev server and renders unstyled pages — delete it.

## Windows dev environment quirks

- The `composer.bat` shim eats `^` in version constraints (cmd escape char); use `php <phpdir>\composer.phar require "pkg:^X"` for carets.
- OneDrive marks new directories ReadOnly/reparse-point, which breaks PHP `is_writable()` (e.g. `bootstrap/cache`); fix with `attrib -r <dir> /s /d`.
- Stripe/Brevo credentials are placeholders in `.env`; the payment-method page degrades gracefully (`stripeConfigured()` check in `PaymentMethodForm`) rather than erroring when unset.
