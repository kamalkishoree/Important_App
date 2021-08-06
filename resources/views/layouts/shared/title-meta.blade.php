@php
    $image = Cache::get('clientdetails');
@endphp
<meta charset="utf-8" />
<title>{{$title ?? ' '}} | {{$image->name??'Royo'}}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Powered by  {{$image->name??'Royo'}} Dispatch. Fleet Management and Last Mile Delivery solution." name="description" />
<meta content=" {{$image->name??'Royo'}} Apps" name="author" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- App favicon -->
<link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
