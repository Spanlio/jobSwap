<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Employer approval') }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-50 font-sans text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
    <div class="mx-auto max-w-2xl px-4 py-12">
        <div class="mb-8 text-center">
            <x-brand-logo class="text-2xl" />
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Employer approval request') }}</p>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-card sm:p-8 dark:border-zinc-800 dark:bg-zinc-900">
            @if (session('status') === 'submitted')
                <div class="flex items-start gap-3 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    <p>{{ __('Thank you — your response has been recorded.') }} {{ __('You can close this page.') }}</p>
                </div>
            @elseif ($expired)
                <div class="rounded-xl bg-zinc-50 p-4 text-sm text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    {{ __('This link has expired. Please contact JobSwap.lv if you still need to respond.') }}
                </div>
            @elseif ($approval->status !== 'pending')
                <div class="rounded-xl bg-zinc-50 p-4 text-sm text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    {{ __('You have already responded to this request.') }}
                    <span class="font-semibold">{{ __(str($approval->status)->headline()->toString()) }}</span>
                </div>
            @else
                <h1 class="text-xl font-bold tracking-tight">{{ __('Employee job swap request') }}</h1>
                <p class="mt-2 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                    {{ __('One of your employees would like to swap jobs with an employee at another company, arranged through JobSwap.lv. Both employers must approve before anything changes.') }}
                </p>

                <dl class="mt-6 grid grid-cols-2 gap-x-4 gap-y-5 rounded-xl bg-zinc-50 p-5 text-sm dark:bg-zinc-800/60">
                    <div>
                        <dt class="font-medium text-zinc-400">{{ __('Current role') }}</dt>
                        <dd class="mt-0.5 font-semibold">{{ $post->current_job_title }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-zinc-400">{{ __('Desired role') }}</dt>
                        <dd class="mt-0.5 font-semibold">{{ $post->desired_job_title }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-zinc-400">{{ __('Years of experience') }}</dt>
                        <dd class="mt-0.5 font-semibold">{{ $post->years_experience }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-zinc-400">{{ __('Region') }}</dt>
                        <dd class="mt-0.5 font-semibold">{{ $post->regionLabel() }}</dd>
                    </div>
                    @if ($post->licenses)
                        <div class="col-span-2">
                            <dt class="font-medium text-zinc-400">{{ __('Licenses / certificates') }}</dt>
                            <dd class="mt-0.5 font-semibold">{{ $post->licenses }}</dd>
                        </div>
                    @endif
                </dl>

                <form method="POST" action="{{ route('employer.respond.submit', $approval->token) }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="question" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            {{ __('Questions or comments (optional)') }}
                        </label>
                        <textarea name="question" id="question" rows="3" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white shadow-sm transition focus:border-brand-600 focus:ring-brand-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200"></textarea>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="submit" name="decision" value="approve" class="flex-1 rounded-xl bg-emerald-600 px-4 py-3.5 text-sm font-bold text-white transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                            {{ __('Yes, I approve') }}
                        </button>
                        <button type="submit" name="decision" value="decline" class="flex-1 rounded-xl border border-red-200 bg-white px-4 py-3.5 text-sm font-bold text-red-700 transition hover:border-red-300 hover:bg-red-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:border-red-900 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-950">
                            {{ __('No, I decline') }}
                        </button>
                    </div>

                    <p class="text-center text-xs leading-relaxed text-zinc-400">
                        {{ __('This link is unique to you — no account or password is needed. Your decision is final and both employees are notified automatically.') }}
                    </p>
                </form>
            @endif
        </div>
    </div>
</body>
</html>
