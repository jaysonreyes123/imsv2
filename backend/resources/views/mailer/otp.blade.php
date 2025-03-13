<x-mail::message>
# Hello {{$user->firstname}},

<br>

For added security, we require a verification code to complete your login. Please use the code below to proceed:

<br>

Your 2FA Code: <span style="color:black"> **{{$token}}** </span>


<br>

This code will expire in 10 minutes. If you did not attempt to log in, please ignore this email or contact our support team immediately.

<br>

Stay secure,
{{ config('app.name') }}
</x-mail::message>
