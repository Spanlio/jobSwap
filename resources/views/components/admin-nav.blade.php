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
                'rounded-md px-3 py-1.5 text-sm font-medium',
                'bg-gray-800 text-white dark:bg-gray-200 dark:text-gray-800' => request()->routeIs($route),
                'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' => ! request()->routeIs($route),
            ])
        >
            {{ $label }}
        </a>
    @endforeach
</nav>
