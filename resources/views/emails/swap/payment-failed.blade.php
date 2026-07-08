<x-mail::message>
# We couldn't process your payment

We were unable to reserve the €{{ number_format(config('jobswap.swap_fee_cents') / 100, 2) }} swap fee on your saved card, so this swap has been cancelled.

No charge has been made. You can add a different payment method and ask to swap again.

<x-mail::button :url="route('payment-method.edit')">
Update payment method
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
