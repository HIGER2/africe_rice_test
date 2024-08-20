@component('mail::message')

{{$data}}

Thank you,<br>
{{ config('app.name') }}
@endcomponent
