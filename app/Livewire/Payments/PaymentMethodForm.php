<?php

namespace App\Livewire\Payments;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PaymentMethodForm extends Component
{
    public string $status = '';

    public function mount(): void
    {
        if (! $this->stripeConfigured()) {
            return;
        }

        $user = Auth::user();

        if (! $user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }
    }

    public function getIntentClientSecretProperty(): string
    {
        if (! $this->stripeConfigured()) {
            return '';
        }

        return Auth::user()->createSetupIntent()->client_secret;
    }

    public function paymentMethodSaved(string $paymentMethodId): void
    {
        Auth::user()->updateDefaultPaymentMethod($paymentMethodId);

        $this->status = 'saved';
        $this->dispatch('payment-method-saved');
    }

    protected function stripeConfigured(): bool
    {
        return filled(config('cashier.key')) && filled(config('cashier.secret'));
    }

    public function render()
    {
        return view('livewire.payments.payment-method-form', [
            'defaultPaymentMethod' => Auth::user()->hasDefaultPaymentMethod() ? Auth::user()->defaultPaymentMethod() : null,
        ]);
    }
}
