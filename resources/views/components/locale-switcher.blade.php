@php
    $current = app()->getLocale();
@endphp

<div class="flex items-center gap-1 text-xs font-medium text-gray-400">
    @foreach (['lv' => 'LV', 'en' => 'EN'] as $code => $label)
        <form method="POST" action="{{ route('locale.update', $code) }}">
            @csrf
            <button
                type="submit"
                @class([
                    'rounded px-1.5 py-0.5',
                    'bg-gray-800 text-white dark:bg-gray-200 dark:text-gray-800' => $current === $code,
                    'hover:text-gray-700 dark:hover:text-gray-200' => $current !== $code,
                ])
            >
                {{ $label }}
            </button>
        </form>
    @endforeach
</div>
