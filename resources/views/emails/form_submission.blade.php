<x-mail::message>
# New request submitted N° {{$form->request_number}}

Hello,

The staff **{{ $user->firstName." ".$user->lastName }}** has submitted the departure form for Bouaké.
The departure request is for **{{$form->depart_date}}**.
The taking up of office is scheduled for **{{$form->taking_date}}**.

{{ config('app.name') }}

</x-mail::message>
