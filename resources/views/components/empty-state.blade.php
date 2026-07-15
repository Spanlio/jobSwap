@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-dashed border-zinc-300 bg-white/50 px-6 py-12 text-center dark:border-zinc-700 dark:bg-zinc-800/30']) }}>
    <p class="font-medium text-zinc-700 dark:text-zinc-200">{{ $title }}</p>
    @if ($description)
        <p class="mx-auto mt-1 max-w-sm text-sm text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-5 flex justify-center">{{ $slot }}</div>
    @endif
</div>
