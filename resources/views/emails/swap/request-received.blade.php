<x-mail::message>
# You have a new swap request

Another worker would like to swap jobs with you based on your post:

**Your role:** {{ $swapRequest->post->current_job_title }}
**Their role:** {{ $swapRequest->requesterPost->current_job_title }} → {{ $swapRequest->requesterPost->desired_job_title }}

Review their profile and approve or decline the request.

<x-mail::button :url="route('swaps.mine')">
Review request
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
