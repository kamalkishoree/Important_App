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
                    <h4 class="page-title">Configure</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form method="POST" action="{{ route('preference', Auth::user()->code) }}">
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
                                            {{ isset($preference) && $preference->map_type == 'google_maps' ? 'selected' : '' }}>
                                            Google Maps</option>
                                    </select>
                                    @if ($errors->has('map_type'))
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
                                        class="form-control" value="{{ old('map_key_1', $preference->map_key_1 ?? '') }}">
                                    @if ($errors->has('map_key_1'))
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
                                @if ($errors->has('map_key_1'))
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
                                @if ($errors->has('map_key_2'))
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


        <div class="row">

            <div class="col-xl-6">
                <form method="POST" action="{{ route('preference', Auth::user()->code) }}">
                    @csrf
                    <div class="card-box same-size">
                        <h4 class="header-title">SMS</h4>
                        <p class="sub-header">
                            Choose between multiple SMS gateways available for ready use or else configure ROYO dispatcher
                            SMS
                            service here
                        </p>
                        <div class="row mb-2">

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sms_provider">CURRENT SELECTION</label>
                                    <select class="form-control" id="sms_provider" name="sms_provider">
                                        <option value="Twilio"
                                            {{ isset($preference) && $preference->sms_provider == 'Twilio' ? 'selected' : '' }}>
                                            Twilio</option>
                                    </select>
                                    @if ($errors->has('sms_provider'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('sms_provider') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sms_provider_number">Number</label>
                                    <input type="text" name="sms_provider_number" id="sms_provider_number"
                                        placeholder="+17290876681" class="form-control"
                                        value="{{ old('sms_provider_number', $preference->sms_provider_number ?? '') }}">
                                    @if ($errors->has('sms_provider_number'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('sms_provider_number') }}</strong>
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
                                        placeholder="Account Sid" class="form-control"
                                        value="{{ old('sms_provider_key_1', $preference->sms_provider_key_1 ?? '') }}">
                                    @if ($errors->has('sms_provider_key_1'))
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
                                        placeholder="Auth Token" class="form-control"
                                        value="{{ old('sms_provider_key_2', $preference->sms_provider_key_2 ?? '') }}">
                                    @if ($errors->has('sms_provider_key_2'))
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
                </form>
            </div>



            <div class="col-xl-5 ">
                <form method="POST" action="{{ route('preference', Auth::user()->code) }}">
                    @csrf
                    <div class="card-box same-size">
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
                                        value="{{ old('personal_access_token_v1', $preference->personal_access_token_v1 ?? '') }}">
                                    @if ($errors->has('personal_access_token_v1'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('personal_access_token_v1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="personal_access_token_v2" class="row">
                                        <span class="col-md-6 col-6">V2 API KEYS</span>
                                        <span class="text-right col-6 col-md-6"><a
                                                href="javascript: genrateKeyAndToken();">Generate Key</a></span>
                                    </label>
                                    <input type="text" name="personal_access_token_v2" id="personal_access_token_v2"
                                        placeholder="No API key found.." class="form-control"
                                        value="{{ old('personal_access_token_v2', $preference->personal_access_token_v2 ?? '') }}">
                                    @if ($errors->has('personal_access_token_v2'))
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
                </form>
            </div>

        </div>


        <div style="display:none;">
            <form method="POST" action="{{ route('preference', Auth::user()->code) }}">
                @csrf
                <div class="row">
                    <div class="col-xl-11 col-md-offset-1">
                        <div class="card-box">
                            <h4 class="header-title">Email</h4>
                            <p class="sub-header">
                                Choose Email paid plan to whitelable "From email address" and "Sender Name" in the Email
                                sent
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
                                                {{ isset($preference) && $preference->email_plan == 'free' ? 'selected' : '' }}>
                                                Free</option>
                                            <option value="paid"
                                                {{ isset($preference) && $preference->email_plan == 'paid' ? 'selected' : '' }}>
                                                Paid</option>
                                        </select>
                                        @if ($errors->has('email_plan'))
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



   
    <!-- end page title -->
    {{-- <div class="row">
        <div class="col-11">
            <div class="card">
                <div class="card-body">
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
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-blue waves-effect waves-light sub-client_modal" data-toggle="modal" data-target="#add-sub-client-modal" data-backdrop="static" data-keyboard="false" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Client</button>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100" id="agents-datatable">
                            <thead>
                                <tr>
                                    <th>Uid</th>
                                    <th>Name</th>
                                    <th>email</th>
                                    <th>phone_number</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($subClients as $subClient)
                                <tr> 
                                    
                                    <td>
                                        {{ $subClient->uid }}
                                    </td>
                                    <td>
                                        {{ $subClient->name }}
                                    </td>
                                    <td>
                                        {{ $subClient->email }}
                                    </td>
                                    <td>
                                        {{ $subClient->phone_number }}
                                    </td>
                                    <td>
                                        {{ $subClient->status == 1 ? 'Active' :'In-active' }}
                                    </td>
                                
                                    
                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div"> <a href="{{ route('subclient.edit', $subClient->id) }}" class="action-icon editIcon" > <i class="mdi mdi-square-edit-outline"></i></a></div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{ route('subclient.destroy', $subClient->id) }}">   
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete"></i></button>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div> --}}


        



    </div> <!-- container -->
    @include('modals.add-sub-client') 
@endsection

@section('script')

    <script type="text/javascript">
        function toggleDisplayCustomDomain() {
            $("#custom_domain_name").toggle('fast', function() {

            });
        }

        function generateRandomString(length) {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for (var i = 0; i < length; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }

        function genrateKeyAndToken() {
            var key = generateRandomString(30);
            var token = generateRandomString(60);

            $('#personal_access_token_v1').val(key);
            $('#personal_access_token_v2').val(token);
        }

    </script>

@endsection
