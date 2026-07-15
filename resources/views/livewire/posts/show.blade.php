<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-zinc-500 transition hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200">
        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd"/></svg>
        {{ __('Back to browse') }}
    </a>

    <div class="mt-4 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-card sm:p-8 dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-[11px] font-semibold uppercase tracking-widest text-zinc-400">{{ $post->user->handle }}</p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-50">
                    {{ $post->current_job_title }}
                </h1>
                <p class="mt-2 flex items-center gap-2 text-lg text-zinc-500 dark:text-zinc-400">
                    <svg class="h-5 w-5 text-brand-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd"/></svg>
                    <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $post->desired_job_title }}</span>
                </p>

                <dl class="mt-8 grid grid-cols-2 gap-x-4 gap-y-6 text-sm sm:grid-cols-3">
                    <div>
                        <dt class="font-medium text-zinc-400">{{ __('Region') }}</dt>
                        <dd class="mt-1 font-semibold text-zinc-800 dark:text-zinc-200">{{ $post->regionLabel() }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-zinc-400">{{ __('Experience') }}</dt>
                        <dd class="mt-1 font-semibold text-zinc-800 dark:text-zinc-200">{{ $post->years_experience }} {{ __('years') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-zinc-400">{{ __('Availability') }}</dt>
                        <dd class="mt-1 font-semibold text-zinc-800 dark:text-zinc-200">{{ $post->availabilityLabel() }}</dd>
                    </div>
                    @if ($post->licenses)
                        <div class="col-span-2 sm:col-span-3">
                            <dt class="font-medium text-zinc-400">{{ __('Licenses / certificates') }}</dt>
                            <dd class="mt-1 leading-relaxed text-zinc-700 dark:text-zinc-300">{{ $post->licenses }}</dd>
                        </div>
                    @endif
                </dl>

                <p class="mt-8 border-t border-zinc-100 pt-4 text-xs text-zinc-400 dark:border-zinc-800 dark:text-zinc-500">
                    {{ __('Posted') }} {{ $post->created_at->diffForHumans() }} &middot;
                    {{ __('Expires') }} {{ $post->expires_at->translatedFormat('j M Y') }}
                </p>
            </div>
        </div>

        <div class="space-y-4">
            @auth
                @if ($isOwner)
                    <div class="rounded-2xl border border-dashed border-zinc-300 bg-white/50 p-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800/30 dark:text-zinc-400">
                        {{ __('This is your post. Other workers can message you from here.') }}
                    </div>
                @elseif ($conversation)
                    <livewire:chat.conversation-thread :conversation="$conversation" :key="$conversation->id" />
                @else
                    <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-card dark:border-zinc-800 dark:bg-zinc-900">
                        <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Message') }} {{ $post->user->handle }}</p>
                        <form wire:submit="startConversation" class="mt-3 space-y-3">
                            <textarea wire:model="newMessage" rows="3" placeholder="{{ __('Say hello and ask about the role…') }}" class="block w-full rounded-lg border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400 shadow-sm transition focus:border-brand-600 focus:ring-brand-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200"></textarea>
                            <x-input-error :messages="$errors->get('newMessage')" />
                            <x-primary-button class="w-full">{{ __('Send message') }}</x-primary-button>
                        </form>
                        <p class="mt-3 text-center text-xs text-zinc-400">{{ __('No commitment — this just opens a chat.') }}</p>
                    </div>
                @endif

                @unless ($isOwner)
                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 text-sm dark:border-zinc-800 dark:bg-zinc-800/40">
                        <p class="font-semibold text-zinc-700 dark:text-zinc-300">{{ __('How swapping works') }}</p>
                        <ol class="mt-3 space-y-2 text-zinc-500 dark:text-zinc-400">
                            <li class="flex gap-2"><span class="font-bold text-brand-600">1.</span> {{ __('Chat and agree together') }}</li>
                            <li class="flex gap-2"><span class="font-bold text-brand-600">2.</span> {{ __('Request the swap — the other worker approves') }}</li>
                            <li class="flex gap-2"><span class="font-bold text-brand-600">3.</span> {{ __('Both employers confirm by email') }}</li>
                            <li class="flex gap-2"><span class="font-bold text-brand-600">4.</span> {{ __('€5 each, only when everything is confirmed') }}</li>
                        </ol>
                    </div>
                @endunless
            @else
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 text-center shadow-card dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Sign in to message this worker.') }}</p>
                    <a href="{{ route('login') }}" wire:navigate class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700 dark:bg-zinc-100 dark:text-zinc-900">
                        {{ __('Log in') }}
                    </a>
                    <p class="mt-3 text-xs text-zinc-400">
                        {{ __('No account yet?') }}
                        <a href="{{ route('register') }}" wire:navigate class="font-semibold text-brand-700 hover:underline dark:text-brand-400">{{ __('Sign up') }}</a>
                    </p>
                </div>
            @endauth
        </div>
    </div>
</div>
