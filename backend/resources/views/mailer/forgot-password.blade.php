<x-mail::message>
# Dear {{$user}},

We've received a request to reset the password for your account. To complete the process and regain access, please click the link below:

If you didn't request this password reset, please disregard this email. Your account 
security is important to us, so we recommend taking the following precautions:
* Ensure your password is unique and not easily guessable.
* Contact our support team immediately if you notice any suspicious activity.

<x-mail::button :url="$url" color="error">
Reset password here
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
