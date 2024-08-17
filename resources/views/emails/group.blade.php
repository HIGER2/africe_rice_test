@component('mail::message')
# Bonjour,

{{ $data }}

Merci,<br>
{{ config('app.name') }}
@endcomponent
