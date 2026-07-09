<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <x-admin-nav />

    <div class="mb-4">
        <select wire:model.live="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
            <option value="">{{ __('All statuses') }}</option>
            <option value="active">{{ __('Active') }}</option>
            <option value="swapped">{{ __('Swapped') }}</option>
            <option value="expired">{{ __('Expired') }}</option>
            <option value="removed">{{ __('Removed') }}</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Worker') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Post') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Region') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">{{ __('Expires') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                @foreach ($posts as $post)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $post->user->handle }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $post->current_job_title }} &rarr; {{ $post->desired_job_title }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $post->regionLabel() }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($post->status) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $post->expires_at->translatedFormat('j M Y') }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            @if ($post->status === 'active')
                                <button type="button" wire:click="remove({{ $post->id }})" wire:confirm="{{ __('Remove this post?') }}" class="text-red-600 hover:text-red-800 dark:text-red-400">{{ __('Remove') }}</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $posts->links() }}</div>
</div>
