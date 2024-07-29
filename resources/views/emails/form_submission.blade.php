{{-- resources/views/emails/form_submission.blade.php --}}

@component('mail::message')
# Nouveau formulaire soumis

Bonjour,

L'utilisateur **{{ $user->firstName." ".$user->lastName }}** a soumis un nouveau formulaire.
{{--
@component('mail::button', ['url' => $url])
Voir les détails
@endcomponent --}}

Merci de vérifier.

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
