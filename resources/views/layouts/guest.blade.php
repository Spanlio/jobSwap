<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'JobSwap.lv') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-screen flex-col items-center bg-zinc-50 pt-6 sm:justify-center sm:pt-0 dark:bg-zinc-950">
            <div>
                <a href="/" wire:navigate class="focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">
                    <x-brand-logo class="text-2xl" />
                </a>
            </div>

            <div class="mt-6 w-full overflow-hidden bg-white px-6 py-6 shadow-card sm:max-w-md sm:rounded-2xl sm:border sm:border-zinc-200 dark:bg-zinc-900 dark:sm:border-zinc-800">
                {{ $slot }}
            </div>

            <p class="mt-6 px-4 text-center text-xs text-zinc-400 dark:text-zinc-500">
                {{ __('Anonymous job swaps for Latvia. Nothing changes without both employers saying yes.') }}
            </p>
        </div>
    </body>
</html>
