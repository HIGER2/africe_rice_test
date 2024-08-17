@component('mail::message')
# Nouveau formulaire soumis

Bonjour,

L'utilisateur **{{ $user->firstName." ".$user->lastName }}**
 a soumis un nouveau demande de départ pour le **{{$form->depart_date}}**.

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
