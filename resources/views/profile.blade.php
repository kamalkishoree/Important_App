@extends('layouts.vertical', ['title' => 'Profile'])

@section('css')
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.18/css/intlTelInput.css'>
<style>
.input-group {
position: relative;
display: block;
flex-wrap: nowrap;
align-items: end;
width: 100%;
}
.iti {
    width: 100%;
}
</style>
@endsection
@php
    $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
    $is_readonly = '';
    $is_disabled = '';
    if(Auth::user()->is_superadmin != 1){
        $is_readonly = 'readonly';
        $is_disabled = 'disabled';
    }
@endphp
@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="page-title-box">
                <h4 class="page-title">{{__("Profile")}}</h4>
            </div>
        </div>
    </div>

    <div class="text-sm-left">
        @if (\Session::has('success'))
        <div class="alert alert-success">
            <span>{!! \Session::get('success') !!}</span>
        </div>
        @endif
    </div>

    <div class="text-sm-left">
        @if (\Session::has('error'))
        <div class="alert alert-error">
            <span>{!! \Session::get('error') !!}</span>
        </div>
        @endif
    </div>
    <!-- end page title -->
    <!-- <div class="row">
        <div class="col-xl-11 col-md-offset-1">
            <div class="card-box" id="test">
                <h4 class="header-title">Organization details</h4>
                <p class="sub-header">
                    View and edit your organization's profile details.
                </p>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <img src="{{asset('assets/images/users/user-3.jpg')}}"
                            class="rounded-circle img-thumbnail avatar-xl" alt="profile-image">
                    </div>
                </div>
                <form id="submitForm">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="nameInput">
                                <label for="name">NAME</label>
                                <input type="text" name="name" id="name" placeholder="ABC Deliveries"
                                    class="form-control">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="emailInput">
                                <label for="email">EMAIL</label>
                                <input type="text" name="email" id="email" placeholder="abc@gmail.com"
                                    class="form-control">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="countryInput">
                                <label for="country">COUNTRY</label>
                                <select class="form-control" id="country">
                                    <option value="india">India</option>
                                    <option value="india">Australia</option>
                                    <option value="india">Dubai</option>
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="timezoneInput">
                                <label for="timezone">TIMEZONE</label>
                                <select class="form-control" id="timezone">
                                    <option value="Asia/Calcutta">(GMT+5:30) Asia/Calcutta</option>

                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                    <span class="show_all_error invalid-feedback"></span>
                </form>
            </div>
        </div>
    </div> -->

        <div class="row">
            <form id="UpdateClient" method="post" action="{{route('profile.update',Auth::user()->code)}}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{__("Organization details")}}</h4>
                            <p class="sub-header">
                                {{__("View and edit your organization's profile details.")}}
                            </p>
                            <div class="row mb-3">
                                <div class="col-md-7">
                                    <div class="row d-flex align-items-center mb-3">
                                        <div class="col-md-4 upload_box">
                                            <input type="file" data-plugins="dropify" name="logo" data-default-file="{{isset(Auth::user()->logo) ? Storage::disk('s3')->url(Auth::user()->logo) : ''}}" />
                                            <p class="text-muted text-center mt-2 mb-0">{{__("Upload Light Logo")}} </p>
                                        </div>
                                        <div class="col-md-4 upload_box">
                                            <input type="file" data-plugins="dropify" name="dark_logo" data-default-file="{{isset(Auth::user()->dark_logo) ? Storage::disk('s3')->url(Auth::user()->dark_logo) : ''}}" />
                                            <p class="text-muted text-center mt-2 mb-0">{{__("Upload Dark Logo")}} </p>
                                        </div>
                                        <div class="col-md-4 upload_box">
                                            <input type="file" class="dropify" data-plugins="dropify" name="favicon" data-default-file="{{ isset($preference->favicon) ? Storage::disk('s3')->url($preference->favicon) : '' }}" />
                                            <p class="text-muted text-center mt-2 mb-0">{{__("Upload favicon")}} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="row mb-2">
                                        <a class="col-md-6" href="https://apps.apple.com/us/app/royo-dispatcher/id1546990347" target="_blank"><img class="w-100" src="{{asset('assets/images/iosstore.png')}}" alt="image" > </a>
                                        <a class="col-md-6 " href="https://play.google.com/store/apps/details?id=com.codebew.deliveryagent&hl=en_US&gl=US" target="_blank"><img class="w-100" src="{{asset('assets/images/playstore.png')}}" alt="image"  > </a>
                                    </div>
                                    <div class="row">
                                        <label class="control-label col-6">{{__("Short Code")}}</label>
                                        <h1 class="control-label col-6" style="font-size: 20px;">{{Auth::user()->code}}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name" class="control-label">{{__("NAME")}}</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', Auth::user()->name ?? '')}}" placeholder="John Doe">
                                        @if($errors->has('name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email" class="control-label">{{__("EMAIL")}}</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '')}}" placeholder="{{__("Enter email address")}}" {{ $is_readonly }}>
                                        @if($errors->has('email'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone_number" class="control-label">{{__("CONTACT NUMBER")}}</label>
                                        <div class="input-group w-100">
                                            <input style="width:100%;" type="text" class="form-control" name="phone_number" id="phone_number" value="{{ old('phone_number', Auth::user()->phone_number ?? '')}}">
                                        </div>
                                        @if($errors->has('phone_number'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('phone_number') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="company_address" class="control-label">{{__("COMPANY ADDRESS")}}</label>
                                        <input type="text" class="form-control" id="company_address" name="company_address" value="{{ old('company_address', Auth::user()->company_address ?? '')}}" placeholder="{{__("Enter company address")}}">
                                        @if($errors->has('company_address'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('company_address') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="company_name" class="control-label">{{__("COMPANY NAME")}}</label>
                                        <input type="text" class="form-control" name="company_name" id="company_name" value="{{ old('company_name', Auth::user()->company_name ?? '')}}" placeholder="Enter company name" {{ $is_readonly }}>
                                        @if($errors->has('company_name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('company_name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3" id="countryInput">
                                        <label for="country">{{__("COUNTRY")}}</label>
                                        @if($errors->has('country'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                        @endif
                                        <select class="form-control" id="country" name="country" value="{{ old('country', $client->id ?? '')}}" placeholder="{{__("Country")}}">
                                            @foreach($countries as $code=>$country)
                                            <option value="{{ $country->id }}" @if(Auth::user()->country_id == $country->id) selected @endif  {{ $is_disabled }}>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3" id="timezoneInput">
                                        <label for="timezone">{{__("TIMEZONE")}}</label>
                                        @if($errors->has('timezone'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('timezone') }}</strong>
                                        </span>
                                        @endif
                                        <select class="form-control" id="timezone" name="timezone" value="{{ old('timezone', $client->timezone ?? '')}}" placeholder="{{__("Timezone")}}">
                                            @foreach($tzlist as $tz)
                                            {{-- <option value="{{ $tz }}" @if(Auth::user()->timezone == $tz) selected @endif>{{ $tz }}</option> --}}
                                            <option value="{{ $tz->id }}" @if(Auth::user()->timezone == $tz->id) selected @endif  {{ $is_disabled }}>{{ $tz->timezone.' ('.$tz->diff_from_gtm.')' }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-blue waves-effect waves-light">{{__("Update")}}</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="col-md-8  mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{route('client.password.update')}}">
                            @csrf
                            <h4 class="header-title">{{__("Change Password")}}</h4>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="old_password">{{__("Old Password")}}</label>
                                        <div class="input-group input-group-merge ">
                                            <input class="form-control " name="old_password" type="password" required="" id="old_password" placeholder={{__("Enter your old password")}}>
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has('old_password'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="password">{{__("New Password")}}</label>
                                        <div class="input-group input-group-merge ">
                                            <input class="form-control " name="password" type="password" required="" id="password" placeholder={{__("Enter your password")}}>
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has('password'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="confirm_password">{{__("Confirm Password")}}</label>
                                        <div class="input-group input-group-merge ">
                                            <input class="form-control " name="password_confirmation" type="password" required="" id="confirm_password" placeholder={{__("Enter your confirm password")}}>
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has('password_confirmation'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-blue btn-block" type="submit"> {{__("Update")}} </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>





</div> <!-- container -->
@endsection

@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="{{asset('assets/js/storeClients.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.18/js/intlTelInput.min.js"></script>

<script>
    var input = document.querySelector("#phone_number");
    var iti = window.intlTelInput(input, {
        separateDialCode:true,
        preferredCountries:["{{getCountryCode()}}"],
        initialCountry:"{{getCountryCode()}}",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.18/js/utils.js",
    });
    
    $('.intl-tel-input').css('width', '100%');


    $(function() {
        $('#phone_number').focus(function() {
            $('#phone_number').css('color', '#6c757d');
        });
    });
</script>
@endsection
