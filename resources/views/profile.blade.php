
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
                <div class="card-box">
                    <h4 class="header-title">Organization details</h4>
                    <p class="sub-header">
                        View and edit your organization's profile details.         
                    </p>
                    
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <img src="{{asset('assets/images/users/user-3.jpg')}}" class="rounded-circle img-thumbnail avatar-xl" alt="profile-image">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">NAME</label>
                                <input type="text" name="name" id="name" placeholder="ABC Deliveries" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email">EMAIL</label>
                                <input type="text" name="email" id="email" placeholder="abc@gmail.com" class="form-control">
                            </div>
                        </div>
                        
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="country">COUNTRY</label>
                                <select class="form-control" id="country">
                                    <option>India</option>
                                    <option>Australia</option>
                                    <option>Dubai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="timezone">TIMEZONE</label>
                                <select class="form-control" id="timezone">
                                    <option>(GMT+5:30) Asia/Calcutta</option>
                                    
                                </select>
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
                </div> 
            </div> 
        </div>
        
    </div> <!-- container -->
@endsection

@section('script')
@endsection