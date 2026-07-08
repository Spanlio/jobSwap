<x-mail::message>
# Swap not approved

Unfortunately one of the employers involved did not approve this job swap, so it will not go ahead.

No charge has been made — any reserved amount has been released back to your card automatically.

Your post remains active and visible to other workers, so you can keep browsing or wait for a new request.

<x-mail::button :url="route('posts.mine')">
View my posts
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
