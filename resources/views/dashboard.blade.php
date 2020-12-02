@extends('layouts.vertical', ['title' => 'Dashboard','demo'=>'creative'])

@section('css')
    <!-- Plugins css -->

@endsection

@section('content')
    <!-- Start Content-->
    <!doctype html>
    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
        <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('demo/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link
            href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="{{ asset('demo/css/propeller.min.css') }}" rel="stylesheet" type="text/css" />
        <title>Landing Page</title>
    </head>

    <body>
        <!-- Header section -->
        <!-- <header class="fixed-top bg-white">
                      <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="#"><img src="images/logo.png"></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                        </button>
                
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                          <ul class="navbar-nav ml-auto display-flex align-items-center">
                            <li class="nav-item">
                              <a class="nav-link" href="">Map</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="">Table</a>          
                            </li>
                            <li class="nav-item active">
                              <a class="nav-link" href="">Analytics</a>          
                            </li>
                          </ul>
                          <ul class="navbar-nav ml-auto display-flex align-items-center">
                            <li class="nav-item brdr-1 pr-3">
                                <div class="dropdown">
                                <button class="create-task dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Create Task <span class="ml-1 mr-1">|</span> 
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="#">Action</a>
                                  <a class="dropdown-item" href="#">Action</a>
                                  <a class="dropdown-item" href="#">Action</a>
                                </div>
                                </div>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href=""><img src="images/ic_upload.png"></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href=""><img src="images/ic_download.png"></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href=""><img src="images/ic_notification.png"></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href=""><img src="images/ic_settings.png"></a>
                            </li>
                          </ul>
                      </div>
                    </nav>
                  </header> -->
        <!-- Bannar Section -->
        <section class="bannar header-setting">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 left-sidebar">
                        <div id="accordion" class="accordion mt-4">
                            <div class="card mb-0">
                                <div class="card-header collapsed" data-toggle="collapse" href="#collapseOne">
                                    <a class="card-title">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <img src="{{ asset('demo/images/ic_unassigned_tasks.png') }}">
                                            </div>
                                            <div class="col-md-9 pl-0">
                                                <h6 class="mb-0">Unassigned Tasks</h6>
                                                <p class="mb-0">3 Tasks</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div id="collapseOne" class="card-body collapse" data-parent="#accordion">
                                </div>
                                @foreach ($teams as $item)
                                    <div class="card-header" data-toggle="collapse" data-parent="#accordion"
                              href="{{$item->id}}" aria-expanded="true">
                                        <a class="card-title">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <img src="{{ asset('demo/images/ic_assigned_to.png') }}">
                                                </div>
                                                <div class="col-md-9 pl-0">
                                                <h6 class="mb-0">{{$item->name}}</h6>
                                                    <p class="mb-0">2 Agents : <span>1 Busy ・ 1 Inactive</span></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    @foreach ($item->agents as $agent)
                                    <div id="{{$item->id}}" class="card-body collapse show" data-parent="#accordion"
                                      style="">
                                        <a class="profile-block" data-toggle="collapse" href="#collapseExample"
                                            aria-expanded="true" aria-controls="collapseExample">

                                            <div class="profile-status">
                                                <i class="fa fa-angle-up"></i>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <img class="profile-image"
                                                            src="https://dummyimage.com/36x36/ccc/fff">
                                                    </div>
                                                    <div class="col-md-10">
                                                    <h6 class="mb-0">{{$agent->name}}</h6>
                                                        <p class="mb-0">Busy ・ <span>2 Tasks</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    
                                      </div>
                                      @foreach ($agent->order as $orders)
                                       @foreach ($orders->task as $tasks)
                                       <div class="collapse show" id="collapseExample">
                                        <div class="card card-body brdr profile-card">
                                            <div class="p-3 assigned-block">
                                                <div class="wd-10">
                                                    <img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                                </div>
                                                <div class="wd-90">
                                                    <h6>347 Ford Center Apt. 820 Chandigarh</h6>
                                                    <span>Fátima Cambeiro</span>
                                                    <h5 class="mb-1"><span>Pickup before</span> 06:30 pm</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <a class="view-task-details" href="">View Task Details</a>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <button class="assigned-btn">Assigned</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                       @endforeach
                                          
                                      @endforeach
                                    @endforeach
                                  
                                @endforeach


                                <div class="collapse show" id="collapseExample">
                                    <div class="card card-body brdr profile-card">
                                        <div class="p-3 assigned-block">
                                            <div class="wd-10">
                                                <img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                            </div>
                                            <div class="wd-90">
                                                <h6>347 Ford Center Apt. 820 Chandigarh</h6>
                                                <span>Fátima Cambeiro</span>
                                                <h5 class="mb-1"><span>Pickup before</span> 06:30 pm</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <a class="view-task-details" href="">View Task Details</a>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <button class="assigned-btn">Assigned</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="" data-toggle="collapse" href="#multiCollapseExample1" aria-expanded="false"
                                    aria-controls="multiCollapseExample1">
                                    <div class="profile-status deactivated">
                                        <i class="fa fa-angle-up"></i>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <img class="profile-image" src="https://dummyimage.com/36x36/ccc/fff">
                                            </div>
                                            <div class="col-md-10">
                                                <h6 class="mb-0">Harrison Philips</h6>
                                                <p class="mb-0">Inactive ・ <span>2 Tasks</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="collapse multi-collapse" id="multiCollapseExample1">
                                    <div class="card card-body brdr profile-card">
                                        <div class="p-3 assigned-block">
                                            <div class="wd-10">
                                                <img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                            </div>
                                            <div class="wd-90">
                                                <h6>347 Ford Center Apt. 820 Chandigarh</h6>
                                                <span>Fátima Cambeiro</span>
                                                <h5 class="mb-1"><span>Pickup before</span> 06:30 pm</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <a class="view-task-details" href="">View Task Details</a>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <button class="assigned-btn">Assigned</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 assigned-block bg-white">
                                            <div class="wd-10">
                                                <img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                            </div>
                                            <div class="wd-90">
                                                <h6>347 Ford Center Apt. 820 Chandigarh</h6>
                                                <span>Fátima Cambeiro</span>
                                                <h5 class="mb-1"><span>Pickup before</span> 06:30 pm</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <a class="view-task-details" href="">View Task Details</a>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <button class="completed-btn">Completed</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="card-header collapsed" data-toggle="collapse" data-parent="#accordion"
                                href="#collapseThree">
                                <a class="card-title">
                                    <a class="card-title">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <img src="{{ asset('demo/images/ic_assigned_to.png') }}">
                                            </div>
                                            <div class="col-md-9 pl-0">
                                                <h6 class="mb-0">Mumbai Team</h6>
                                                <p class="mb-0">2 Agents : <span>1 Busy ・ 1 Inactive</span></p>
                                            </div>
                                        </div>
                                    </a>
                            </div>
                            <div id="collapseThree" class="collapse" data-parent="#accordion">
                                <div class="card card-body brdr profile-card">
                                    <div class="p-3 assigned-block">
                                        <div class="wd-10">
                                            <img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                        </div>
                                        <div class="wd-90">
                                            <h6>347 Ford Center Apt. 820 Chandigarh</h6>
                                            <span>Fátima Cambeiro</span>
                                            <h5 class="mb-1"><span>Pickup before</span> 06:30 pm</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <a class="view-task-details" href="">View Task Details</a>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="assigned-btn">Assigned</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 assigned-block bg-white">
                                        <div class="wd-10">
                                            <img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                        </div>
                                        <div class="wd-90">
                                            <h6>347 Ford Center Apt. 820 Chandigarh</h6>
                                            <span>Fátima Cambeiro</span>
                                            <h5 class="mb-1"><span>Pickup before</span> 06:30 pm</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <a class="view-task-details" href="">View Task Details</a>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="completed-btn">Completed</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header collapsed" data-toggle="collapse" data-parent="#accordion"
                                href="#collapseFour">
                                <a class="card-title">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="{{ asset('demo/images/ic_assigned_to.png') }}">
                                        </div>
                                        <div class="col-md-9 pl-0">
                                            <h6 class="mb-0">Delhi Team</h6>
                                            <p class="mb-0">2 Agents : <span>1 Busy ・ 1 Inactive</span></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div id="collapseFour" class="card-body collapse" data-parent="#accordion">
                                <div class="p-3">

                                </div>
                            </div>
                            <div class="card-header collapsed" data-toggle="collapse" data-parent="#accordion"
                                href="#collapseFive">
                                <a class="card-title">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="{{ asset('demo/images/ic_assigned_to.png') }}">
                                        </div>
                                        <div class="col-md-9 pl-0">
                                            <h6 class="mb-0">Delhi Team 2</h6>
                                            <p class="mb-0">2 Agents : <span>1 Busy ・ 1 Inactive</span></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div id="collapseFive" class="card-body collapse" data-parent="#accordion">
                                <div class="p-3">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="map-wrapper">
                        <div style="width: 100%"><iframe width="100%" height="700" frameborder="0" scrolling="no"
                                marginheight="0" marginwidth="0"
                                src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=30.7334421,76.7797143+()&amp;t=&amp;z=17&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe><a
                                href="https://www.maps.ie/route-planner.htm"></a></div>
                        <div class="contant">
                            <div class="bottom-content">
                                <input type="text" class="brdr-1 datetime" value="10/05/2016">
                                <div class="dropdown d-inline-block brdr-1">
                                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img class="mr-1" src="{{ asset('demo/images/ic_assigned_to.png') }}">2 Teams
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <div class="task-block pl-3 pr-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span>Tasks</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <a href=""><span>All</span></a>
                                                    <a class="ml-3" href=""><span>None</span></a>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6>All Teams</h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6>Delhi Team</h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6>Mumbai Team</h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6>Chandigarh Team</h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox" checked="checked">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown d-inline-block brdr-1">
                                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img class="mr-1" src="{{ asset('demo/images/ic_time.png') }}">All Tasks
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <div class="task-block pl-3 pr-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span>Drivers</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <a href=""><span>All</span></a>
                                                    <a class="ml-3" href=""><span>None</span></a>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6><img class="mr-2"
                                                            src="{{ asset('demo/images/ic_location_blue_1.png') }}"></span>Unassigned
                                                    </h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6><img class="mr-2"
                                                            src="{{ asset('demo/images/ic_location_green_1.png') }}"></span>Assigned
                                                    </h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6><img class="mr-2"
                                                            src="{{ asset('demo/images/ic_location_blue_1.png') }}"></span>In
                                                        transit</h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6><img class="mr-2"
                                                            src="{{ asset('demo/images/ic_location_green_1.png') }}"></span>Completed
                                                    </h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-9">
                                                    <h6><img class="mr-2"
                                                            src="{{ asset('demo/images/ic_location_green_1.png') }}"></span>Failed
                                                    </h6>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox" checked="checked">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown d-inline-block">
                                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img class="mr-1" src="{{ asset('demo/images/ic_time.png') }}">All Drivers
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <div class="task-block pl-3 pr-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span>Drivers</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <a href=""><span>All</span></a>
                                                    <a class="ml-3" href=""><span>None</span></a>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6><span class="circle mr-3"></span>All Teams</h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6><span class="circle mr-2"></span>Offline</h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6><span class="circle in-transit mr-2"></span>In transit</h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <h6><span class="circle lia-castro mr-2"></span>Lia Castro</h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-9">
                                                    <h6><span class="circle lia-castro mr-2"></span>Lubomír Dvořák</h6>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <label class="">
                                                        <input cla type="checkbox" checked="checked">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
        </script>
        <script src="{{ asset('demo/js/propeller.min.js') }}"></script> --}}
    </body>

    </html>
@endsection
