
@extends('layouts.vertical', ['title' => 'Auto Allocation'])

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
                    <h4 class="header-title">Options</h4>
                    
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                <p class="sub-header">
                                    Enable this option to automatically  assign Task to your agent.         
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" checked="" class="custom-control-input" id="auto_allocation">
                                <label class="custom-control-label" for="auto_allocation"></label>
                            </div>
                        </div>
                    </div>
                    <h4 class="header-title">Select a method to allocate task</h4>
                    
                    <div class="row mb-2 mt-2">
                        <div class="col-md-4">
                            <div class="border p-3 rounded mb-3">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio1" name="shippingOptions" class="custom-control-input" checked="">
                                    <label class="custom-control-label font-16 font-weight-bold" for="shippingMethodRadio1">One By One</label>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio2" name="shippingOptions" class="custom-control-input">
                                    <label class="custom-control-label font-16 font-weight-bold" for="shippingMethodRadio2">Send to all</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio3" name="shippingOptions" class="custom-control-input">
                                    <label class="custom-control-label font-16 font-weight-bold" for="shippingMethodRadio3">Batch Wise</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio4" name="shippingOptions" class="custom-control-input">
                                    <label class="custom-control-label font-16 font-weight-bold" for="shippingMethodRadio4">Round Robin</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio5" name="shippingOptions" class="custom-control-input">
                                    <label class="custom-control-label font-16 font-weight-bold" for="shippingMethodRadio5">Nearest Available</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio6" name="shippingOptions" class="custom-control-input">
                                    <label class="custom-control-label font-16 font-weight-bold" for="shippingMethodRadio6">First In, First Out</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2">
                        <div class="col-md-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-2">
                                    <img src="{{asset('assets/images/onebyone.png')}}" alt="img" title="img" class="rounded" height="90">
                                </div>
                                <div class="col-md-10">
                                    <h4 class="header-title">One By One</h4>
                                    <p class="sub-header">Send the task notification to the agent nearest to the task location. If the agent doesn't accept the task within request expiry time, the task request is send to the next nearest Agent. If no Agent accepts the task, it remains unassigned.</p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-2 mt-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="task_priority">TASK ALLOCATION PRIORITY</label>
                                <select class="form-control" id="task_priority">
                                    <option>Default</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="request_expire_in_sec">REQUEST EXPIRES IN SEC</label>
                                <input type="text" name="request_expire_in_sec"  id="request_expire_in_sec" placeholder="30" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="no_of_retries">NO. OF RETRIES</label>
                                <input type="text" name="no_of_retries"  id="no_of_retries" placeholder="0" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="start_allocation_before_task_time">START ALLOCATION BEFORE TASK TIME (IN MINUTES)</label>
                                <input type="text" name="start_allocation_before_task_time" id="start_allocation_before_task_time" placeholder="0" class="form-control">
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


        
        
    </div>
@endsection

@section('script')
@endsection