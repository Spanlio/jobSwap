<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('posts.create') }}" wire:navigate class="rounded-lg border border-gray-200 bg-white p-6 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ __('Post a swap') }}</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Publish a new anonymous swap offer.') }}</p>
                </a>
                <a href="{{ route('home') }}" wire:navigate class="rounded-lg border border-gray-200 bg-white p-6 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ __('Browse') }}</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Find a swap that matches your role.') }}</p>
                </a>
                <a href="{{ route('swaps.mine') }}" wire:navigate class="rounded-lg border border-gray-200 bg-white p-6 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ __('My swaps') }}</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Approve, decline or track your requests.') }}</p>
                </a>
                <a href="{{ route('messages.index') }}" wire:navigate class="rounded-lg border border-gray-200 bg-white p-6 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ __('Messages') }}</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Chat with other workers.') }}</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
