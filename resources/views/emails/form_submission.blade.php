<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <style>
        /* Ajoutez ici des styles pour les boutons */
        .button {
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            border-radius: 5px;
            margin: 5px;
        }
        .green {
            background-color: green;
        }
        .red {
            background-color: red;
        }
    </style>
</head>
<body>
    <h1>New request submitted N° {{ $form->request_number }}</h1>

    <p>Hello,</p>

    <p>The staff <strong>{{ $user->firstName." ".$user->lastName }}</strong> has submitted the departure form for Bouaké.</p>
    <p>The departure request is for <strong>{{ $form->depart_date }}</strong>.</p>
    <p>The taking up of office is scheduled for <strong>{{ $form->taking_date }}</strong>.</p>

    <a class="button green" href="{{ URL::signedRoute('form.confirm', ['id' => $form->id, 'action' => 'approve']) }}">Approve</a>
    <a class="button red" href="{{ URL::signedRoute('form.confirm', ['id' => $form->id, 'action' => 'reject']) }}">Reject</a>

    <p>{{ config('app.name') }}</p>

    {{--
    <p>Thank you for reviewing this.</p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
    --}}
</body>
</html>
