
test test test

{{-- @component('mail::message')
# New request submitted N° {{$form->request_number}}

Hello,

The staff **{{ $user->firstName." ".$user->lastName }}** has submitted the departure form for Bouaké.
The departure request is for **{{$form->depart_date}}**.
The taking up of office is scheduled for **{{$form->taking_date}}**.

@component('mail::button', ['url' => URL::signedRoute('form.confirm', ['id' => $form->id, 'action' => 'approve']), 'color' => 'green'])
Approve
@endcomponent

@component('mail::button', ['url' => URL::signedRoute('form.confirm', ['id' => $form->id, 'action' => 'reject']), 'color' => 'red'])
Reject
@endcomponent --}}
{{-- {{ config('app.name') }} --}}

{{--
Thank you for reviewing this.

Best regards,<br>

{{ config('app.name') }} --}}
{{-- @endcomponent --}}
