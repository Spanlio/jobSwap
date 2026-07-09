<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Employer approval') }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 font-sans text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100">
    <div class="mx-auto max-w-2xl px-4 py-12">
        <div class="mb-8 text-center">
            <p class="text-lg font-semibold tracking-tight">{{ config('app.name') }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Employer approval request') }}</p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-8">
            @if (session('status') === 'submitted')
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800 dark:bg-green-950 dark:text-green-200">
                    {{ __('Thank you — your response has been recorded.') }}
                </div>
            @elseif ($expired)
                <div class="rounded-md bg-gray-50 p-4 text-sm text-gray-600 dark:bg-gray-900 dark:text-gray-300">
                    {{ __('This link has expired. Please contact JobSwap.lv if you still need to respond.') }}
                </div>
            @elseif ($approval->status !== 'pending')
                <div class="rounded-md bg-gray-50 p-4 text-sm text-gray-600 dark:bg-gray-900 dark:text-gray-300">
                    {{ __('You have already responded to this request.') }}
                    <span class="font-medium">{{ __(str($approval->status)->headline()->toString()) }}</span>
                </div>
            @else
                <h1 class="text-xl font-semibold">{{ __('Employee job swap request') }}</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('One of your employees would like to swap jobs with an employee at another company, arranged through JobSwap.lv. Both employers must approve before anything changes.') }}
                </p>

                <dl class="mt-6 grid grid-cols-2 gap-4 rounded-md bg-gray-50 p-4 text-sm dark:bg-gray-900">
                    <div>
                        <dt class="text-gray-400">{{ __('Current role') }}</dt>
                        <dd class="font-medium">{{ $post->current_job_title }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">{{ __('Desired role') }}</dt>
                        <dd class="font-medium">{{ $post->desired_job_title }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">{{ __('Years of experience') }}</dt>
                        <dd class="font-medium">{{ $post->years_experience }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">{{ __('Region') }}</dt>
                        <dd class="font-medium">{{ $post->regionLabel() }}</dd>
                    </div>
                    @if ($post->licenses)
                        <div class="col-span-2">
                            <dt class="text-gray-400">{{ __('Licenses / certificates') }}</dt>
                            <dd class="font-medium">{{ $post->licenses }}</dd>
                        </div>
                    @endif
                </dl>

                <form method="POST" action="{{ route('employer.respond.submit', $approval->token) }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Questions or comments (optional)') }}
                        </label>
                        <textarea name="question" id="question" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="submit" name="decision" value="approve" class="flex-1 rounded-md bg-gray-800 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-700 dark:bg-gray-200 dark:text-gray-800">
                            {{ __('Yes, I approve') }}
                        </button>
                        <button type="submit" name="decision" value="decline" class="flex-1 rounded-md border border-red-300 px-4 py-3 text-sm font-semibold text-red-700 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-950">
                            {{ __('No, I decline') }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</body>
</html>
