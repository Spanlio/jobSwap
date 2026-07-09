@props(['status'])

@php
    $styles = match ($status) {
        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'awaiting_employers' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
        'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'declined_by_worker', 'declined_by_employer', 'cancelled', 'payment_failed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium whitespace-nowrap $styles"]) }}>
    {{ __(str($status)->headline()->toString()) }}
</span>
