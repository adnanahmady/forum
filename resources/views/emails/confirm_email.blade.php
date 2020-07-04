@component('mail::message')

    # One last step

    Please confirm your email for having full abilities on your account

@component('mail::button', ['url' => url('/register/confirm?token='.$user->confirm_token)])
Confirm Me
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
