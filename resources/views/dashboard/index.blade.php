
@extends('layouts.vertical', ['title' => 'Dashboard','demo'=>'creative'])
{{-- Variable section --}}
@php
    use Carbon\Carbon;
    $color = ['one','two','three','four','five','six','seven','eight'];
    $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
    $agent_lat_longs = json_encode(@$agentMarkerData);
   
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
    <script>
        var agentsLatLong =`<?php  echo $agent_lat_longs  ?>`;
        var channelname = "orderdata{{ $client_code }}{{ date('Y-m-d', time()) }}";
        var logchannelname = "agentlog{{ $client_code }}{{ date('Y-m-d', time()) }}";
        var imgproxyurl = {!! json_encode($imgproxyurl) !!};
        var optimizeRouteUrl = "{{ url('/optimize-route') }}";
        var optimizeArrangeRouteUrl = "{{ url('/optimize-arrange-route') }}";
        var assignAgentUrl = "{{ route('assign.agent') }}";
        var getRouteDetailUrl = "{{ route('get-route-detail') }}";
        var X_CSRF_TOKEN = '{{ csrf_token() }}';
        var iconsRoute = "{{ asset('assets/newicons/') }}";
        var teamDataUrl = "{{ route('dashboard.teamsdata') }}";
        var orderDataUrl = "{{ route('dashboard.orderdata') }}";
        var channelName = "orderdata{{ $client_code }}";
        var logChannelName = "agentlog{{ $client_code }}";
        var dashboardTheme = "{{ $dashboard_theme }}";
        var exportPathUrl = "{{ url('/export-path') }}";
        var getTasks = "{{ url('/get-tasks') }}";
        var arrangeRoute  = "{{ url('/arrange-route') }}";
        var getAgentNomenclature = "{{ __(getAgentNomenclature()) }}";
    
    </script>
     @include("dashboard/parts/layout-$dashboard_theme/bottom")
@endsection


