@extends('layouts.vertical', ['title' => 'Profile'])

@section('css')
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
    <div class="row">
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
    </div>

</div> <!-- container -->
@endsection

@section('script')
<script src="{{asset('assets/js/storeClients.js')}}"></script>
@endsection