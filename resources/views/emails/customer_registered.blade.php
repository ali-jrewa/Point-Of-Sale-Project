<p>{{ __('emails.hello') }} {{ $customer->first_name }} {{ $customer->last_name }},</p>

<p>{{ __('emails.customer_registration_thanks') }}</p>

<p>{{ __('emails.customer_code') }}: <strong>{{ $customer->customer_code }}</strong></p>

<p>{{ __('emails.regards') }},<br>{{ config('app.name') }}</p>
