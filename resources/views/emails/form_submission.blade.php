<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
</head>
<body>
    <h1>New request submitted N° {{ $form->request_number }}</h1>

    <p>Hello,</p>

    <p>The staff <strong>{{ $user->firstName." ".$user->lastName }}</strong> has submitted the departure form for Bouaké.</p>
    <p>The departure request is for <strong>{{ $form->depart_date }}</strong>.</p>
    <p>The taking up of office is scheduled for <strong>{{ $form->taking_date }}</strong>.</p>

    {{-- <table role="presentation" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>
                <button style="padding: 10px 20px; background-color: green; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    <a href="{{ URL::signedRoute('form.confirm', ['id' => $form->id, 'action' => 'approve']) }}" style="text-decoration: none; color: white;">Approve</a>
                </button>
            </td>
            <td>
                <button style="padding: 10px 20px; background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    <a href="{{ URL::signedRoute('form.confirm', ['id' => $form->id, 'action' => 'reject']) }}" style="text-decoration: none; color: white;">Reject</a>
                </button>
            </td>
        </tr>
    </table> --}}

    <p>{{ config('app.name') }}</p>
</body>
</html>
