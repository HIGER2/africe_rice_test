@component('mail::message')
# Nouveau formulaire soumis

Bonjour,

L'employé **{{ $user->firstName." ".$user->lastName }}**
 a soumis un nouveau demande de départ pour le **{{$form->depart_date}}**.
 prise de fonction **{{$form->taking_date}}**
 montant à verser **{{$form->total_amount}} CFA**

{{--
@component('mail::button', ['url' => route('form.action', ['id' => $form->id, 'action' => 'approve']), 'color' => 'green'])
Approuver
@endcomponent

@component('mail::button', ['url' => route('form.action', ['id' => $form->id, 'action' => 'reject']), 'color' => 'red'])
Rejeter
@endcomponent --}}
{{--
Merci de vérifier.

Cordialement,<br>
{{ config('app.name') }} --}}
@endcomponent
