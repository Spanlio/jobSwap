<div>
    @if ($defaultPaymentMethod)
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                {{ __('Card on file') }}: <span class="font-medium uppercase">{{ $defaultPaymentMethod->card->brand }}</span>
                &bull;&bull;&bull;&bull; {{ $defaultPaymentMethod->card->last4 }}
            </div>
            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                {{ __('Ready for swaps') }}
            </span>
        </div>
        <button type="button" x-data x-on:click="$wire.set('defaultPaymentMethod', null)" class="mt-3 text-sm text-gray-500 underline hover:text-gray-800 dark:text-gray-400">
            {{ __('Replace card') }}
        </button>
    @endif

    <div class="{{ $defaultPaymentMethod ? 'hidden' : '' }}" x-data="stripePaymentMethodForm({
            publishableKey: @js(config('cashier.key')),
            clientSecret: @js($this->intentClientSecret),
        })" x-init="init()">
        <div wire:ignore>
            <div id="payment-element" class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"></div>
        </div>

        <p x-show="!publishableKey" class="mt-3 text-sm text-amber-600 dark:text-amber-400">
            {{ __('Payments are not configured yet. Set STRIPE_KEY / STRIPE_SECRET to enable card collection.') }}
        </p>

        <p x-show="error" x-text="error" class="mt-3 text-sm text-red-600 dark:text-red-400"></p>

        <button
            type="button"
            x-on:click="submit()"
            x-bind:disabled="!publishableKey || loading"
            class="mt-4 inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 disabled:opacity-40 dark:bg-gray-200 dark:text-gray-800"
        >
            <span x-show="!loading">{{ __('Save card') }}</span>
            <span x-show="loading">{{ __('Saving…') }}</span>
        </button>
    </div>
</div>

@script
<script>
    Alpine.data('stripePaymentMethodForm', ({ publishableKey, clientSecret }) => ({
        publishableKey,
        clientSecret,
        stripe: null,
        elements: null,
        loading: false,
        error: null,

        async init() {
            if (! this.publishableKey) {
                return;
            }

            await new Promise((resolve) => {
                if (window.Stripe) return resolve();
                const script = document.createElement('script');
                script.src = 'https://js.stripe.com/v3/';
                script.onload = resolve;
                document.head.appendChild(script);
            });

            this.stripe = window.Stripe(this.publishableKey);
            this.elements = this.stripe.elements({ clientSecret: this.clientSecret });
            this.elements.create('payment').mount('#payment-element');
        },

        async submit() {
            if (! this.stripe || ! this.elements) return;

            this.loading = true;
            this.error = null;

            const { error, setupIntent } = await this.stripe.confirmSetup({
                elements: this.elements,
                redirect: 'if_required',
            });

            this.loading = false;

            if (error) {
                this.error = error.message;
                return;
            }

            $wire.paymentMethodSaved(setupIntent.payment_method);
        },
    }));
</script>
@endscript
