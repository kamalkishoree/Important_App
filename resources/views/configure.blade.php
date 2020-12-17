@extends('layouts.vertical', ['title' => 'Configure'])

@section('css')
@endsection
@php
   // dd($preference);
@endphp
@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Settings</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <form method="POST" action="{{route('preference', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Map Configuration</h4>
                    <p class="sub-header">
                        View and update your Map type and it's API key.
                    </p>
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="currency">MAP TYPE</label>
                                <select class="form-control" id="map_type" name="map_type">
                                    <option value="google_maps"
                                        {{ (isset($preference) && $preference->map_type =="google_maps")? "selected" : "" }}>
                                        Google Maps</option>
                                </select>
                                @if($errors->has('map_type'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('map_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="map_key_1">API KEY</label>
                                <input type="text" name="map_key_1" id="map_key_1" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('map_key_1', $preference->map_key_1 ?? '')}}">
                                @if($errors->has('map_key_1'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('map_key_1') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="map_key_1">API KEY</label>
                                <input type="text" name="map_key_1" id="map_key_1" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('map_key_1', $preference->map_key_1 ?? '')}}">
                                @if($errors->has('map_key_1'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('map_key_1') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="map_key_2">TRACKING LINK API KEY</label>
                                <input type="text" name="map_key_2" id="map_key_2" placeholder="No key added.."
                                    class="form-control" value="{{ old('map_key_2', $preference->map_key_2 ?? '')}}">
                                @if($errors->has('map_key_2'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('map_key_2') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form method="POST" action="{{route('preference', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">SMS</h4>
                    <p class="sub-header">
                        Choose between multiple SMS gateways available for ready use or else configure ROYO dispatcher
                        SMS
                        service here
                    </p>
                    <div class="row mb-0">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_provider">CURRENT SELECTION</label>
                                <select class="form-control" id="sms_provider" name="sms_provider">
                                    <option value="Twilio"
                                        {{ (isset($preference) && $preference->sms_provider =="Twilio")? "selected" : "" }}>
                                        Twilio</option>
                                </select>
                                @if($errors->has('sms_provider'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sms_provider') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row mb-0">
                        <div class="col-md-6">
                            <p class="sub-header">
                                To Configure your Bumbl SMS Service, go to <a href="#">Bumble Dashboard</a>
                            </p>
                        </div>
                    </div> --}}
                    <div class="row mb-2">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_provider_key_1">API KEY</label>
                                <input type="text" name="sms_provider_key_1" id="sms_provider_key_1"
                                    placeholder="asdada324234fd32" class="form-control"
                                    value="{{ old('sms_provider_key_1', $preference->sms_provider_key_1 ?? '')}}">
                                @if($errors->has('sms_provider_key_1'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sms_provider_key_1') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_provider_key_2">API Secret</label>
                                <input type="text" name="sms_provider_key_2" id="sms_provider_key_2"
                                    placeholder="asdada324234fd32" class="form-control"
                                    value="{{ old('sms_provider_key_2', $preference->sms_provider_key_2 ?? '')}}">
                                @if($errors->has('sms_provider_key_2'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sms_provider_key_2') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div style="display:none;">
    <form method="POST" action="{{route('preference', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Email</h4>
                    <p class="sub-header">
                        Choose Email paid plan to whitelable "From email address" and "Sender Name" in the Email sent
                        out
                        from your account.
                    </p>
                    <div class="row mb-0">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email_plan">CURRENT SELECTION</label>
                                <select class="form-control" id="email_plan" name="email_plan">
                                    <option>Select Plan</option>
                                    <option value="free"
                                        {{ (isset($preference) && $preference->email_plan =="free")? "selected" : "" }}>
                                        Free</option>
                                    <option value="paid"
                                        {{ (isset($preference) && $preference->email_plan =="paid")? "selected" : "" }}>
                                        Paid</option>
                                </select>
                                @if($errors->has('email_plan'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('email_plan') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="sms_service_api_key">PREVIEW</label>
                                <div class="card">
                                    <div class="card-body">

                                        <p class="mb-2"><span class="font-weight-semibold mr-2">From:</span>
                                            johndoe<span>
                                                << /span>contact@royodispatcher.com<span>></span>
                                        </p>
                                        <p class="mb-2"><span class="font-weight-semibold mr-2">Reply To:</span>
                                            johndoe@gmail.com</p>

                                        <p class="mt-3 text-center">
                                            Your message hore here..
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
    <form method="POST" action="{{route('preference', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Personal Access Token</h4>
                    <p class="sub-header">
                        View and Generate API keys.
                    </p>
                    <div class="row mb-2">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="personal_access_token_v1">V1 API ACCESS TOKEN</label>
                                <input type="text" name="personal_access_token_v1" id="personal_access_token_v1"
                                    placeholder="kjadsasd66asdas" class="form-control"
                                    value="{{ old('personal_access_token_v1', $preference->personal_access_token_v1 ?? '')}}">
                                @if($errors->has('personal_access_token_v1'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('personal_access_token_v1') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="personal_access_token_v2" class="row">
                                    <span class="col-md-6">V2 API KEYS</span>
                                    <span class="text-right col-md-6"><a href="javascript: genrateKeyAndToken();">Generate Key</a></span>
                                </label>
                                <input type="text" name="personal_access_token_v2" id="personal_access_token_v2"
                                    placeholder="No API key found.." class="form-control"
                                    value="{{ old('personal_access_token_v2', $preference->personal_access_token_v2 ?? '')}}">
                                @if($errors->has('personal_access_token_v2'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('personal_access_token_v2') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- <form method="POST" action="{{route('preference', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Domain</h4>
                    <p class="sub-header">
                        Choose the domain you want to publish your platform on.
                    </p>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class=" p-1 mb-3">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="domain_type_1" value="domain" name="domain_name"
                                        class="custom-control-input"
                                        {{ (isset($preference) && $preference->domain_name =="domain")? "checked" : "" }}>
                                    <label class="custom-control-label font-16 font-weight-bold"
                                        for="domain_type_1">{{ $client->custom_domain }}</label>
                                </div>
                                <p class="mb-0 pl-3 pt-6">Published </p>
                            </div>
                            <div class=" p-1 mb-1">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="domain_type_1" value="domain" name="domain_name"
                                        class="custom-control-input"
                                        {{ (isset($preference) && $preference->domain_name =="domain")? "checked" : "" }}>
                                    <label class="custom-control-label font-16 font-weight-bold"
                                        for="domain_type_1">Main Domain</label>
                                   
                                </div>
                            </div>

                            <div class=" p-1 mb-1">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="domain_type_2" value="custom_domain" name="domain_name"
                                        class="custom-control-input"
                                        {{ (isset($preference) && $preference->domain_name =="custom_domain")? "checked" : "" }}>
                                    <label class="custom-control-label font-16 font-weight-bold"
                                        for="domain_type_2">Custom
                                        Domain</label>
                                    @if(isset($preference) && $preference->domain_name == 'custom_domain')
                                    <p class="mb-0 pl-3 pt-1">{{ $client->custom_domain }}</p>
                                    @endif
                                </div>
                                <div class="custom-control">
                                    <input type="text" name="custom_domain_name" id="custom_domain_name" value="" style="display:none;">
                                </div>
                                <p class="mb-0 pl-3 pt-1"><a href="javascript: toggleDisplayCustomDomain();">Click here</a> to add custom domain</p>
                            </div>

                            @if($errors->has('domain_name'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('domain_name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form> --}}

    

</div> <!-- container -->
@endsection

@section('script')

<script type="text/javascript">
function toggleDisplayCustomDomain(){
    $("#custom_domain_name").toggle( 'fast', function(){ 

    });
}

function generateRandomString(length) {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
   
  for (var i = 0; i < length; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));
   
  return text;
}

function genrateKeyAndToken(){
    var key = generateRandomString(30);
    var token = generateRandomString(60);

    $('#personal_access_token_v1').val(key);
    $('#personal_access_token_v2').val(token);
}

</script>

@endsection