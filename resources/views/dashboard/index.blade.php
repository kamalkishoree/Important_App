
@extends('layouts.vertical', ['title' => 'Dashboard','demo'=>'creative'])

{{-- Variable section --}}
@php
    use Carbon\Carbon;
    $color = ['one','two','three','four','five','six','seven','eight'];
    $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp

{{--End Variable section --}}


@section('css')
    @include("dashboard/parts/layout-$dashboard_theme/top")
@endsection



@section('content')
    @include("dashboard/parts/layout-$dashboard_theme/left")
    @include("dashboard/parts/layout-$dashboard_theme/right")
@endsection




@section('script')
     @include("dashboard/parts/layout-$dashboard_theme/bottom")
@endsection


