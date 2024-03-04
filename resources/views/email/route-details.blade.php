<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document Routing - {{env('APP_NAME')}}</title>
</head>
<body>
<ul>
    <strong>Dear Mr./Mrs. {{ $data['recipient_name'] }}</strong>,
    <br /><br />
    Document <strong>{{ $data['title'] }}</strong> has been successfully <strong>{{ $data['body_status'] }}</strong> by <strong>{{ $data['processed_by'] }}</strong>.
    <br />For more details please click the tracking number 
    <a href="{{ URL::to('route/routing-document/'.$data['encrypted_str']) }}" class="btn btn-sm btn-info">{{ $data['tracking_no'] }}</a>.
    <br /><br />
    Regards,
    <br /><strong>{{env('APP_NAME')}}</strong>

{{-- sa link na --}}
    {{-- @if ($info['payment_type'] == "cod")
        <li>Payment Type: Cash on Delivery (COD)</li>
        <li>Total Amount: {{ $info['total_amount'] }}</li>
        <li>Tracking Code: {{ $info['tracking_code'] }}</li>
    @endif --}}

</ul>
</body>
</html>