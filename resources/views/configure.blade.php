
@extends('layouts.vertical', ['title' => 'Configure'])

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
                    <h4 class="header-title">Map Configuration</h4>
                    <p class="sub-header">
                        View and update your Map type and it's API key.         
                    </p>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="currency">MAP TYPE</label>
                                <select class="form-control" id="map_type">
                                    <option>Google Maps</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="api_key">API KEY</label>
                                <input type="text" name="api_key" id="api_key" placeholder="kjadsasd66asdas" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tracking_link_api_key">TRACKING LINK API KEY</label>
                                <input type="text" name="tracking_link_api_key" id="tracking_link_api_key" placeholder="No key added.." class="form-control">
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

        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">SMS</h4>
                    <p class="sub-header">
                        Choose between multiple SMS gateways available for ready use or else configure ROYO dispatcher SMS service here        
                    </p>
                    <div class="row mb-0">
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_selection_type">CURRENT SELECTION</label>
                                <select class="form-control" id="sms_selection_type">
                                    <option>Bumbl SMS Service</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-6">
                            <p class="sub-header">
                                To Configure your Bumbl SMS Service, go to <a href="#">Bumble Dashboard</a>     
                            </p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_service_api_key">API KEY</label>
                                <input type="text" name="sms_service_api_key" id="sms_service_api_key" placeholder="asdada324234fd32" class="form-control">
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
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Email</h4>
                    <p class="sub-header">
                        Choose Email paid plan to whitelable "From email address" and "Sender Name" in the Email sent out from your account.        
                    </p>
                    <div class="row mb-0">
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email_selection_type">CURRENT SELECTION</label>
                                <select class="form-control" id="email_selection_type">
                                    <option>Free</option>
                                    <option>Paid</option>
                                </select>
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
                                            johndoe<span><</span>contact@royodispatcher.com<span>></span>
                                        </p>
                                        <p class="mb-2"><span class="font-weight-semibold mr-2">Reply To:</span> johndoe@gmail.com</p>
                                        
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
                                <button class="btn btn-primary btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>

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
                                    <input type="radio" id="domain_type_1" name="domain_type" class="custom-control-input" checked="">
                                    <label class="custom-control-label font-16 font-weight-bold" for="domain_type_1">abcdeliveries.royodispatcher.com</label>
                                </div>
                                <p class="mb-0 pl-3 pt-1">Published </p>
                            </div>

                            <div class=" p-1 mb-1">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="domain_type_2" name="domain_type" class="custom-control-input">
                                    <label class="custom-control-label font-16 font-weight-bold" for="domain_type_2">Custom Domain</label>
                                </div>
                                <p class="mb-0 pl-3 pt-1"><a href="javascript:;">Click here</a> to add custom domain</p>
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
                                <label for="access_token">V1 API ACCESS TOKEN</label>
                                <input type="text" name="access_token" id="access_token" placeholder="kjadsasd66asdas" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="v2_api_key" class="row">
                                    <span class="col-md-6">V2 API KEYS</span> 
                                    <span class="text-right col-md-6"><a href="javascript:;">Generate Key</a></span>
                                </label>
                                <input type="text" name="api_key" id="v2_api_key" placeholder="No API key found.." class="form-control">
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