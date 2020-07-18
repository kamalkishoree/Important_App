@extends('layouts.vertical', ['title' => 'Profile'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Organization details</h4>
                    <p class="sub-header">
                        View and edit your organization's profile details.
                    </p>
                    <form id="UpdateClient" method="post" action="{{route('client.update', Auth::user()->id)}}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input type="file" data-plugins="dropify" name="logo"
                                    data-default-file="{{isset(Auth::user()->logo) ? asset('clients/'.Auth::user()->logo.'') : ''}}" />
                                <p class="text-muted text-center mt-2 mb-0">Upload Logo</p>
                            </div>
                        </div>

                        <div class=" row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">NAME</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ old('name', Auth::user()->name ?? '')}}" placeholder="John Doe">
                                    @if($errors->has('name'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="control-label">EMAIL</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', Auth::user()->email ?? '')}}"
                                        placeholder="Enter email address">
                                    @if($errors->has('email'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">+91</span>
                                        </div>
                                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                                            value="{{ old('phone_number', Auth::user()->phone_number ?? '')}}"
                                            placeholder="Enter mobile number">
                                    </div>
                                    @if($errors->has('phone_number'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="custom_domain" class="control-label">CUSTOM DOMAIN</label>
                                    <input type="text" class="form-control" name="custom_domain" id="custom_domain"
                                        value="{{ old('custom_domain', Auth::user()->custom_domain ?? '')}}"
                                        placeholder="Enter custom domain">
                                    @if($errors->has('custom_domain'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('custom_domain') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_path" class="control-label">DATABASE PATH</label>
                                    <input type="text" class="form-control" name="database_path" id="database_path"
                                        value="{{ old('database_path', Auth::user()->database_path ?? '')}}"
                                        placeholder="Enter Path">
                                    @if($errors->has('database_path'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_path') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_name" class="control-label">DATABASE NAME</label>
                                    <input type="text" class="form-control" name="database_name" id="database_name"
                                        value="{{ old('database_name', Auth::user()->database_name ?? '')}}"
                                        placeholder="Enter database name">
                                    @if($errors->has('database_name'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_username" class="control-label">DATABASE USERNAME</label>
                                    <input type="text" class="form-control" name="database_username"
                                        id="database_username"
                                        value="{{ old('database_username', Auth::user()->database_username ?? '')}}"
                                        placeholder="Enter database username">
                                    @if($errors->has('database_username'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_password" class="control-label">DATABASE PASSWORD</label>
                                    <input type="text" class="form-control" name="database_password"
                                        id="database_password"
                                        value="{{ old('database_password', Auth::user()->database_password ?? '')}}"
                                        placeholder="Enter database password">
                                    @if($errors->has('database_password'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name" class="control-label">COMPANY NAME</label>
                                    <input type="text" class="form-control" name="company_name" id="company_name"
                                        value="{{ old('company_name', Auth::user()->company_name ?? '')}}"
                                        placeholder="Enter company name">
                                    @if($errors->has('company_name'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('company_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_address" class="control-label">COMPANY ADDRESS</label>
                                    <input type="text" class="form-control" id="company_address" name="company_address"
                                        value="{{ old('company_address', Auth::user()->company_address ?? '')}}"
                                        placeholder="Enter company address">
                                    @if($errors->has('company_address'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('company_address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group mb-3" id="countryInput">
                                    <label for="country">COUNTRY</label>
                                    <input type="text" class="form-control" id="country" name="country"
                                        value="{{ old('country', Auth::user()->country ?? '')}}" placeholder="Country">
                                    @if($errors->has('country'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                    @endif
                                    <!-- <select class="form-control" id="country">
                                        <option value="india">India</option>
                                        <option value="india">Australia</option>
                                        <option value="india">Dubai</option>
                                    </select> -->
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3" id="timezoneInput">
                                    <label for="timezone">TIMEZONE</label>
                                    <input type="text" class="form-control" id="timezone" name="timezone"
                                        value="{{ old('timezone', Auth::user()->timezone ?? '')}}"
                                        placeholder="Timezone">
                                    @if($errors->has('timezone'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('timezone') }}</strong>
                                    </span>
                                    @endif
                                    <!-- <select class="form-control" id="timezone">
                                        <option value="Asia/Calcutta">(GMT+5:30) Asia/Calcutta</option>

                                    </select> -->
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Update</button>
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
@endsection