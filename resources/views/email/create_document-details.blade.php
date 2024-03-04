<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Document - {{env('APP_NAME')}}</title>
</head>
<body>
<ul>
    <strong>Dear Mr./Mrs. {{ $data['recipient_name'] }}</strong>,
    <br /><br />
    Document <strong>{{ $data['title'] }}</strong> has been sent by <strong>{{ $data['sender_name'] }}</strong> for review.
    <br />To reply, please click the tracking number 
    <a href="{{ URL::to('document/reply/'.$data['hashed_docid'].'/'.$data['hashed_senderid']) }}" class="btn btn-sm btn-info">{{ $data['barcode'] }}</a>.
    <br />
    You may also click here <a href="{{ URL::to('document/received') }}" class="btn btn-sm btn-info">{{env('APP_NAME')}}</a> to visit your inbox.
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