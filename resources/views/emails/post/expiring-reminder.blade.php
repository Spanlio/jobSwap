<x-mail::message>
# Your post expires in {{ config('jobswap.expiry_reminder_days_before') }} days

Your job swap post ({{ $post->current_job_title }} → {{ $post->desired_job_title }}) will expire on {{ $post->expires_at->translatedFormat('j F Y') }}.

Renew it now to keep it visible in the browse feed.

<x-mail::button :url="route('posts.edit', $post)">
Renew my post
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
