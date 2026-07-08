<x-mail::message>
# Your swap is confirmed!

Both employers have approved. €{{ number_format(config('jobswap.swap_fee_cents') / 100, 2) }} has been charged for your side of the swap.

You can now see the other worker's full details and get in touch to arrange the next steps.

**Other worker's contact:** {{ $otherWorker->email }}

<x-mail::button :url="route('swaps.mine')">
View swap details
</x-mail::button>

Congratulations, and good luck in the new role!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
