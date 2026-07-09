<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <x-admin-nav />

    <div class="mb-4">
        <x-text-input wire:model.live.debounce.400ms="search" type="text" class="w-full max-w-sm" :placeholder="__('Search by handle, name or email…')" />
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Handle') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Email') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Role') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Posts') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Status') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->handle }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->role }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->posts_count }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if ($user->is_banned)
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">{{ __('Banned') }}</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">{{ __('Active') }}</span>
                            @endif
                        </td>
                        <td class="space-x-3 px-4 py-3 text-right text-sm">
                            @unless ($user->isAdmin())
                                @if ($user->is_banned)
                                    <button type="button" wire:click="unban({{ $user->id }})" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">{{ __('Unban') }}</button>
                                @else
                                    <button type="button" wire:click="ban({{ $user->id }})" wire:confirm="{{ __('Ban this user?') }}" class="text-amber-600 hover:text-amber-800 dark:text-amber-400">{{ __('Ban') }}</button>
                                @endif
                                <button type="button" wire:click="delete({{ $user->id }})" wire:confirm="{{ __('Permanently delete this user?') }}" class="text-red-600 hover:text-red-800 dark:text-red-400">{{ __('Delete') }}</button>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
