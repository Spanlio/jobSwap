# JobSwap.lv — Anonymous Job Swap Marketplace for Latvia

Workers who want to trade jobs post anonymous swap offers, chat with each other, and request a swap. Both employers approve or decline through a one-time email link — no employer account needed. When everyone has said yes, each worker is charged a €5 fee (€10 total) and the full details are revealed.

## How to start it on your computer (simple version)

Open the project in VS Code, open its terminal (`` Ctrl + ` ``), and:

**The very first time** (downloads everything the site needs and prepares it — takes a few minutes):

```
npm run setup
```

**Every time you want the site running:**

```
npm start
```

Leave that terminal open — it *is* the site. Open your browser at **http://localhost:8000** and you'll see the JobSwap front page with example job posts.

Two test accounts are ready (password for both is `password`):

- Regular worker: `test@example.com`
- Administrator: `admin@jobswap.lv` (this one can open the Admin panel)

To stop the site, click into the terminal and press `Ctrl + C`. Nothing is lost — next time just run `npm start` again.

> Note: emails and card payments are switched off until real Brevo/Stripe accounts are connected (see the technical section below), so you can test everything safely — no real money moves anywhere.

---

## How a swap works

1. **Worker A** posts an anonymous swap offer (job title, desired title, licences, experience, region, availability).
2. **Worker B** finds it in the browse feed and opens a chat — no commitment.
3. Worker B clicks **Request Swap**; Worker A approves or declines.
4. On approval, a **€5 payment is reserved** (Stripe authorization, not a charge) from each worker, and both employers receive an email link.
5. Each employer link shows the full swap details with a Q&A field and a **Yes/No** decision — no registration.
6. When **both employers approve**, the reserved €5 is captured from each worker and full details are revealed to both sides.
7. If **anyone declines** at any stage, the Stripe reservations are released and the post stays active.

Posts expire after 30 days; owners get an email reminder 3 days before expiry. Workers can run multiple active posts.

## Tech stack

- **Laravel 13** + **Livewire 3** — single-page-feel UI without a JS framework
- **Laravel Breeze** — worker authentication
- **Laravel Cashier + Stripe** — manual-capture PaymentIntents (authorize → capture/cancel)
- **Laravel Mail + Brevo SMTP** — all transactional email
- **SQLite** in development, **MySQL/PostgreSQL** in production
- **Tailwind CSS** — minimal, clean UI; LV/EN language toggle throughout

## Configuration

### Stripe

Set in `.env`:

```
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
CASHIER_CURRENCY=eur
SWAP_FEE_CENTS=500
```

Workers add a card at `/payment-method` (Stripe SetupIntent). The swap flow in `app/Services/SwapFlowService.php` creates a manual-capture PaymentIntent per worker when employers are notified, captures both on full confirmation, and cancels them on any decline. If a reservation fails, the swap is cancelled and the counterpart reservation released.

### Email (Brevo)

`.env.example` is preconfigured for Brevo's SMTP relay — fill in `MAIL_USERNAME` / `MAIL_PASSWORD` with your Brevo SMTP credentials. Mailables live in `app/Mail/`.

### Scheduler

Two daily commands (registered in `routes/console.php`):

- `app:expire-posts` — expires posts older than 30 days
- `app:send-expiry-reminders` — emails owners 3 days before a post expires

In production run the standard cron entry:

```
* * * * * cd /var/www/jobswap && php artisan schedule:run >> /dev/null 2>&1
```

## Tests

```bash
php artisan test
```

Feature coverage includes the full swap state machine (request → worker approval → employer approvals → capture/release), employer token links, post expiry, access control, and guest browsing.

## Deploying to Hetzner (Ubuntu 24.04)

1. **Provision** a CX22 (or larger) with Ubuntu, then install the stack:
   ```bash
   apt install nginx mysql-server php8.4-fpm php8.4-{mysql,xml,mbstring,curl,zip,intl,bcmath} composer nodejs npm
   ```
2. **Database**: create a `jobswap` MySQL database and user; set `DB_CONNECTION=mysql` and credentials in `.env`.
3. **Code**: clone the repo to `/var/www/jobswap`, then:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   php artisan key:generate
   php artisan migrate --force
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
4. **Nginx**: point the server block at `public/`, enable HTTPS with certbot.
5. **Cron**: add the `schedule:run` entry above.
6. **Queue** (recommended, mail is queued): set `QUEUE_CONNECTION=database` and run `php artisan queue:work` under systemd/supervisor.
7. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://...`, live Stripe keys, and Brevo credentials.

## Project layout

- `app/Livewire/` — feed, post form, chat, swaps, payment method form, admin panel
- `app/Services/SwapFlowService.php` — the swap state machine and all Stripe authorize/capture/release logic
- `app/Http/Controllers/EmployerApprovalController.php` — tokenized employer approve/decline pages (no auth)
- `app/Mail/` — all transactional mailables
- `config/jobswap.php` — regions, availability options, swap fee
- `lang/lv/`, `lang/lv.json` — Latvian translations (LV is the default locale)

## For AI agents

The repo is indexed with [codegraph](https://github.com/colbymchenry/codegraph) (`codegraph.json` config at the root). Prefer `codegraph explore <query>` / `codegraph query <symbol>` over grep for symbol lookup, call-flow, and impact questions.
