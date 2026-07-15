@props(['disabled' => false])

<select @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-zinc-300 bg-white text-zinc-900 shadow-sm transition focus:border-brand-600 focus:ring-brand-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:focus:border-brand-500 dark:focus:ring-brand-500']) }}>
    {{ $slot }}
</select>
