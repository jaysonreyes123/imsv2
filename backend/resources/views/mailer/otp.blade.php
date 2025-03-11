<x-mail::message>
# Hello {{$user}},

<br>

Your verification code is: <span style="color:black">**{{$token}}**</span>


<br>

* Use this one-time code to continue logging into your account.
* Contact our support team immediately if you notice any suspicious activity.

<br>

Thanks,

<br>
{{ config('app.name') }}
</x-mail::message>
