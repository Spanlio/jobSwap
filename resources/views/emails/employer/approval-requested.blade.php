<x-mail::message>
# Employee job swap request

One of your employees would like to swap jobs with an employee at another company, arranged through JobSwap.lv, an anonymous job-swap marketplace for Latvia.

**Current role:** {{ $post->current_job_title }}
**Desired role:** {{ $post->desired_job_title }}
**Years of experience:** {{ $post->years_experience }}
**Region:** {{ $post->regionLabel() }}
**Licenses / certificates:** {{ $post->licenses ?: '—' }}

Both employees' current employers must approve before the swap is finalized. Nothing changes until you and the other employer both confirm.

You can leave an optional question or comment for us before deciding.

<x-mail::button :url="$respondUrl">
Review and respond
</x-mail::button>

This link is unique to you and does not require creating an account.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
