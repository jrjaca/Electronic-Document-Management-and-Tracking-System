<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Registration - {{env('APP_NAME')}}</title>
</head>
<body>
<ul>
    <strong>Dear Mr./Mrs. {{ $data['approver_name'] }}</strong>,
    <br /><br />

    <strong>{{ $data['registrant_name'] }}</strong> has successfully registered in your area. <br />
    Please visit <a href="{{ URL::to('user-management/users/registered') }}" class="btn btn-sm btn-info">List of Registered Users</a> to activate the account and assign user role.
    <br /><br />
    Regards,
    <br /><strong>{{env('APP_NAME')}}</strong>

</ul>
</body>
</html>