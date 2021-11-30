@component('mail::message')
# Introduction
<h1>Halo {{ $user_name }}, Selamat Datang! {{ config('app.name') }}</h1>
<p>OTP Code : {{ $otp_code }}</p>
@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
