<div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ $post->user->handle }}</p>

            <h1 class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $post->current_job_title }}
            </h1>
            <p class="mt-1 flex items-center gap-2 text-gray-500 dark:text-gray-400">
                <span>{{ __('wants to swap for') }}</span>
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $post->desired_job_title }}</span>
            </p>

            <dl class="mt-6 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                <div>
                    <dt class="text-gray-400">{{ __('Region') }}</dt>
                    <dd class="mt-1 font-medium text-gray-800 dark:text-gray-200">{{ $post->regionLabel() }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">{{ __('Experience') }}</dt>
                    <dd class="mt-1 font-medium text-gray-800 dark:text-gray-200">{{ $post->years_experience }} {{ __('years') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">{{ __('Availability') }}</dt>
                    <dd class="mt-1 font-medium text-gray-800 dark:text-gray-200">{{ $post->availabilityLabel() }}</dd>
                </div>
            </dl>

            @if ($post->licenses)
                <div class="mt-6">
                    <dt class="text-sm text-gray-400">{{ __('Licenses / certificates') }}</dt>
                    <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $post->licenses }}</dd>
                </div>
            @endif

            <p class="mt-6 text-xs text-gray-400">
                {{ __('Posted') }} {{ $post->created_at->diffForHumans() }} &middot;
                {{ __('Expires') }} {{ $post->expires_at->translatedFormat('j M Y') }}
            </p>
        </div>
    </div>

    <div>
        @auth
            @if ($isOwner)
                <div class="rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                    {{ __('This is your post. Other workers can message you from here.') }}
                </div>
            @elseif ($conversation)
                <livewire:chat.conversation-thread :conversation="$conversation" :key="$conversation->id" />
            @else
                <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                    <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Message') }} {{ $post->user->handle }}</p>
                    <form wire:submit="startConversation" class="space-y-3">
                        <textarea wire:model="newMessage" rows="3" placeholder="{{ __('Say hello and ask about the role…') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                        <x-input-error :messages="$errors->get('newMessage')" />
                        <x-primary-button>{{ __('Send message') }}</x-primary-button>
                    </form>
                    <p class="mt-3 text-xs text-gray-400">{{ __('No commitment — this just opens a chat.') }}</p>
                </div>
            @endif
        @else
            <div class="rounded-lg border border-gray-200 bg-white p-6 text-center dark:border-gray-700 dark:bg-gray-800">
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('Sign in to message this worker.') }}</p>
                <a href="{{ route('login') }}" wire:navigate class="mt-3 inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 dark:bg-gray-200 dark:text-gray-800">
                    {{ __('Log in') }}
                </a>
            </div>
        @endauth
    </div>
</div>
