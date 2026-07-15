<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:border-red-300 hover:bg-red-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 disabled:opacity-40 dark:border-red-900 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-950 dark:focus-visible:ring-offset-zinc-900']) }}>
    {{ $slot }}
</button>
