@php
    $links = [
        'admin.dashboard' => __('Overview'),
        'admin.users' => __('Users'),
        'admin.posts' => __('Posts'),
        'admin.swaps' => __('Swaps'),
        'admin.logs.actions' => __('Swap action log'),
        'admin.logs.posts' => __('Post log'),
    ];
@endphp

<nav class="mb-8 flex flex-wrap gap-2 border-b border-gray-200 pb-4 dark:border-gray-700">
    @foreach ($links as $route => $label)
        <a
            href="{{ route($route) }}"
            wire:navigate
            @class([
                'rounded-lg px-3 py-1.5 text-sm font-semibold transition',
                'bg-ink text-white dark:bg-zinc-100 dark:text-zinc-900' => request()->routeIs($route),
                'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' => ! request()->routeIs($route),
            ])
        >
            {{ $label }}
        </a>
    @endforeach
</nav>
