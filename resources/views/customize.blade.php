
@extends('layouts.vertical', ['title' => 'Customize'])

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
                    <h4 class="header-title">Theme</h4>
                    <p class="sub-header">
                        Choose between light and dark theme, for the platform.         
                    </p>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <p class="text-muted mb-2">SELECT THEME PREFERENCE</p>
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" id="light_theme" value="light" name="theme_option" checked="">
                                <label for="light_theme"> Light theme </label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" id="dark_theme" value="dark" name="theme_option">
                                <label for="dark_theme"> Dark theme </label>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div> 
        </div>

        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Nomenclature</h4>
                    <p class="sub-header">
                        Define and update the nomenclature         
                    </p>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="agent_type">AGENT TYPE</label>
                                <input type="text" name="agent_type" id="agent_type" placeholder="e.g Driver" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="currency">CURRENCY</label>
                                <select class="form-control" id="currency">
                                    <option>INR</option>
                                    <option>USD</option>
                                    <option>AED</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="currency">UNIT</label>
                                <select class="form-control" id="currency">
                                    <option>Kilometers</option>
                                    <option>Meters</option>
                                    <option>Centimeters</option>
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

        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Acknowledgement Type</h4>
                    <p class="sub-header">
                        Agent can either acknowledge the receipt of the task or accept/decline a Task based on your selection below.         
                    </p>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <p class="text-muted mb-2">SELECT PREFERENCE</p>
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" id="acknowledge1" value="acknowledge" name="acknowledgement_type" checked="">
                                <label for="acknowledge1"> Acknowledge </label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" id="acknowledge2" value="acceptreject" name="acknowledgement_type">
                                <label for="acknowledge2"> Accept/Reject </label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" id="acknowledge3" value="none" name="acknowledgement_type">
                                <label for="acknowledge3"> None </label>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div> 
        </div>

        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Date & Time</h4>
                    <p class="sub-header">
                        View and update the date & time format.       
                    </p>
                    <div class="row mb-2">
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="date_format">DATE FORMAT</label>
                                <select class="form-control" id="date_format">
                                    <option>DD-MM-YYYY</option>
                                    <option>DD/MM/YYYY</option>
                                    <option>YYYY-MM-DD</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="time_format">TIME FORMAT</label>
                                <select class="form-control" id="time_format">
                                    <option>12 hours</option>
                                    <option>24 hours</option>
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

        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Tracking URL</h4>
                    <p class="sub-header">
                        Customize the tracking URL.       
                    </p>
                     <p class="sub-header m-0">
                        Preview       
                    </p>
                    <p class="sub-header ">
                        <code>https://royodispatcher.com/tasktrack/Pickup/00023</code>
                    </p>
                    <div class="row mb-2">
                       
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="part_1">PART 1</label>
                                <select class="form-control" id="part_1">
                                    <option>Task Type</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="part_2">PART 2</label>
                                <select class="form-control" id="part_2">
                                    <option>Order Id</option>
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