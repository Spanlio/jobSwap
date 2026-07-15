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
        <div class="flex min-h-screen flex-col bg-zinc-50 dark:bg-zinc-950">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            @auth
                @persist('chat-dock')
                    <livewire:chat.chat-dock />
                @endpersist
            @endauth

            <footer class="mt-16 border-t border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-6 text-sm text-zinc-500 sm:flex-row sm:px-6 lg:px-8 dark:text-zinc-400">
                    <x-brand-logo class="text-base" />
                    <p>{{ __('Anonymous job swaps for Latvia. Nothing changes without both employers saying yes.') }}</p>
                    <x-locale-switcher />
                </div>
            </footer>
        </div>
    </body>
</html>
