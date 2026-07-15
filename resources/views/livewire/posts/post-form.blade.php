<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">
        {{ $post ? __('Edit your swap offer') : __('Post a swap offer') }}
    </h1>
    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
        {{ __('The public part is anonymous — other workers see your roles and region, never your name.') }}
    </p>

    <form wire:submit="save" class="mt-8 space-y-6">
        <!-- Public section -->
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-card sm:p-8 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">{{ __('Public') }}</span>
                <h2 class="font-semibold text-zinc-800 dark:text-zinc-200">{{ __('What other workers see') }}</h2>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <x-input-label for="current_job_title" :value="__('Current job title')" />
                    <x-text-input wire:model="current_job_title" id="current_job_title" class="mt-1 block w-full" type="text" required :placeholder="__('e.g. Electrician')" />
                    <x-input-error :messages="$errors->get('current_job_title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="desired_job_title" :value="__('Desired job title')" />
                    <x-text-input wire:model="desired_job_title" id="desired_job_title" class="mt-1 block w-full" type="text" required />
                    <x-input-error :messages="$errors->get('desired_job_title')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6">
                <x-input-label for="licenses" :value="__('Licenses / certificates (optional)')" />
                <textarea wire:model="licenses" id="licenses" rows="2" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400 shadow-sm transition focus:border-brand-600 focus:ring-brand-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200" placeholder="{{ __('e.g. B category') }}"></textarea>
                <x-input-error :messages="$errors->get('licenses')" class="mt-2" />
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <x-input-label for="years_experience" :value="__('Years of experience')" />
                    <x-text-input wire:model="years_experience" id="years_experience" class="mt-1 block w-full" type="number" min="0" max="60" required />
                    <x-input-error :messages="$errors->get('years_experience')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="region" :value="__('Region')" />
                    <x-select-input wire:model="region" id="region" class="mt-1 block w-full" required>
                        <option value="">{{ __('Select…') }}</option>
                        @foreach ($regions as $value => $labels)
                            <option value="{{ $value }}">{{ $labels[app()->getLocale()] ?? $labels['en'] }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('region')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="availability" :value="__('Availability')" />
                    <x-select-input wire:model="availability" id="availability" class="mt-1 block w-full" required>
                        <option value="">{{ __('Select…') }}</option>
                        @foreach ($availabilityOptions as $value => $labels)
                            <option value="{{ $value }}">{{ $labels[app()->getLocale()] ?? $labels['en'] }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('availability')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Private section -->
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-card sm:p-8 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-950 dark:text-amber-300">
                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                    {{ __('Private') }}
                </span>
                <h2 class="font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Only used if a swap is agreed') }}</h2>
            </div>
            <p class="mt-2 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                {{ __('The fields below are private. They are only used to contact your employer if a swap is fully agreed, and are never shown publicly.') }}
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <x-input-label for="employer_email" :value="__('Employer email')" />
                    <x-text-input wire:model="employer_email" id="employer_email" class="mt-1 block w-full" type="email" required />
                    <x-input-error :messages="$errors->get('employer_email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="employer_name" :value="__('Employer / company name (optional)')" />
                    <x-text-input wire:model="employer_name" id="employer_name" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('employer_name')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('posts.mine') }}" wire:navigate class="text-sm font-medium text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200">
                {{ __('Cancel') }}
            </a>
            <x-primary-button>
                {{ $post ? __('Save changes') : __('Publish post') }}
            </x-primary-button>
        </div>

        <p class="text-right text-xs text-zinc-400 dark:text-zinc-500">{{ __('Your post stays visible for 30 days. You can edit or remove it anytime.') }}</p>
    </form>
</div>
