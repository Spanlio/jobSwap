<div class="space-y-6">
    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="current_job_title" :value="__('Current job title')" />
                <x-text-input wire:model="current_job_title" id="current_job_title" class="mt-1 block w-full" type="text" required />
                <x-input-error :messages="$errors->get('current_job_title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="desired_job_title" :value="__('Desired job title')" />
                <x-text-input wire:model="desired_job_title" id="desired_job_title" class="mt-1 block w-full" type="text" required />
                <x-input-error :messages="$errors->get('desired_job_title')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="licenses" :value="__('Licenses / certificates (optional)')" />
            <textarea wire:model="licenses" id="licenses" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
            <x-input-error :messages="$errors->get('licenses')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div>
                <x-input-label for="years_experience" :value="__('Years of experience')" />
                <x-text-input wire:model="years_experience" id="years_experience" class="mt-1 block w-full" type="number" min="0" max="60" required />
                <x-input-error :messages="$errors->get('years_experience')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="region" :value="__('Region')" />
                <select wire:model="region" id="region" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                    <option value="">{{ __('Select…') }}</option>
                    @foreach ($regions as $value => $labels)
                        <option value="{{ $value }}">{{ $labels[app()->getLocale()] ?? $labels['en'] }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('region')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="availability" :value="__('Availability')" />
                <select wire:model="availability" id="availability" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                    <option value="">{{ __('Select…') }}</option>
                    @foreach ($availabilityOptions as $value => $labels)
                        <option value="{{ $value }}">{{ $labels[app()->getLocale()] ?? $labels['en'] }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('availability')" class="mt-2" />
            </div>
        </div>

        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-900 dark:bg-amber-950">
            <p class="text-sm text-amber-800 dark:text-amber-200">
                {{ __('The fields below are private. They are only used to contact your employer if a swap is fully agreed, and are never shown publicly.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
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

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('posts.mine') }}" wire:navigate class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                {{ __('Cancel') }}
            </a>
            <x-primary-button>
                {{ $post ? __('Save changes') : __('Publish post') }}
            </x-primary-button>
        </div>
    </form>
</div>
