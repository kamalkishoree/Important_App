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
        <link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet"
    type="text/css" />
        <link href="{{asset('assets/libs/clockpicker/clockpicker.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet"
    type="text/css" />
        {{--
        <link href="{{ asset('demo/css/propeller.min.css') }}" rel="stylesheet" type="text/css" />
        --}}
        <title>Landing Page</title>

        <style>
            .mb-0>a {
                display: block;
                position: relative;
            }

            .mb-0>a:after {
                content: "\f078";
                /* fa-chevron-down */
                font-family: 'FontAwesome';
                position: absolute;
                right: 0;
            }

            .mb-0>a[aria-expanded="true"]:after {
                content: "\f077";
                /* fa-chevron-up */
            }

            .card-header {
                height: 70px;
            }

            /* #map_wrapper {
                            height: 400px;

                            #map_canvas {
                                width: 100%;
                                height: 100%;
                            }

                            a {
                                text-decoration: none;

                                &:hover {
                                    text-decoration: underline;
                                }
                            }

                            .clustered-hovercard-content {
                                max-width: 265px;
                                overflow: hidden;
                            }

                            .entity-headline {
                                padding: 3px 0 1px;
                            }

                            .entity-title {
                                font-size: 15px;
                                line-height: 16px;
                                overflow: hidden;
                                padding-bottom: 2px;
                                text-overflow: ellipsis;
                                white-space: nowrap;
                                font-weight: 400;
                            }

                            .entity-short-summary {
                                color: #666666;
                                font-size: 12px;
                                font-weight: 400;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }

                            .entity-summary-line {
                                overflow: hidden;
                                text-overflow: ellipsis;
                                white-space: nowrap;
                            }
                        } */

        </style>
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
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-3 left-sidebar">
                        <div id="accordion">
                            <div class="card">
                                
                                    <div class="card-header" id="heading-1">
                                        <h5 class="mb-0">
                                            <a role="button" data-toggle="collapse" href="#collapse-new"
                                                aria-expanded="true" aria-controls="collapse-new">
                                                <div class="newcheckit">
                                                    <div class="row" class="mb-0">
                                                        <div class="col-md-2">
                                                            <img src="{{ asset('demo/images/ic_assigned_to.png') }}">
                                                        </div>
                                                        <div class="col-md-9 pl-0">
                                                            <h6 class="mb-0">Un-Assigned</h6>
                                                            <p class="mb-0">{{count($unassigned)}} Agents : <span>1 Busy ・ 1 Inactive</span></p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>

                                        </h5>
                                    </div>

                                    <div id="collapse-new" class="collapse" data-parent="#accordion"
                                        aria-labelledby="heading-1">
                                        <div class="card-body">


                                            @foreach ($unassigned as $agent)
                                                <div id="accordion-{{ $agent['id'] }}">
                                                    <div class="card">
                                                        <div class="card-header" id="by{{ $agent['id'] }}">
                                                            <h5 class="mb-0">
                                                                <a class="profile-block collapsed" role="button"
                                                                    data-toggle="collapse" href="#collapse{{ $agent['id'] }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="collapse{{ $agent['id'] }}">
                                                                    <div class="row">
                                                                        <div class="col-md-2">
                                                                            <img class="profile-image"
                                                                                src="https://dummyimage.com/36x36/ccc/fff">
                                                                        </div>
                                                                        <div class="col-md-10">
                                                                            <h6 class="mb-0">{{ $agent['name'] }}</h6>
                                                                            <p class="mb-0">Busy ・ <span>2 Tasks</span></p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <div id="collapse{{ $agent['id'] }}" class="collapse"
                                                            data-parent="#accordion-{{ $agent['id'] }}"
                                                            aria-labelledby="by{{ $agent['id'] }}">
                                                            @foreach ($agent['order'] as $orders)
                                                                @foreach ($orders['task'] as $tasks)
                                                                    <div class="card-body">
                                                                        <div class="p-3 assigned-block">
                                                                            <div class="wd-10">
                                                                                <img class="vt-top"
                                                                                    src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                                                            </div>
                                                                            <div class="wd-90">
                                                                                <h6>{{ $tasks['location']['address'] }}</h6>
                                                                                <span>{{ $tasks['location']['short_name'] }}</span>
                                                                                <h5 class="mb-1"><span>Pickup before</span>
                                                                                    06:30 pm
                                                                                </h5>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <a class="view-task-details"
                                                                                            href="">View Task
                                                                                            Details</a>
                                                                                    </div>
                                                                                    <div class="col-md-6 text-right">
                                                                                        <button
                                                                                            class="assigned-btn">Assigned</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                
                            </div>
                            <div class="card">
                                @foreach ($teams as $item)
                                    <div class="card-header" id="heading-1">
                                        <h5 class="mb-0">
                                            <a role="button" data-toggle="collapse" href="#collapse-{{ $item['id'] }}"
                                                aria-expanded="true" aria-controls="collapse-{{ $item['id'] }}">
                                                <div class="newcheckit">
                                                    <div class="row" class="mb-0">
                                                        <div class="col-md-2">
                                                            <img src="{{ asset('demo/images/ic_assigned_to.png') }}">
                                                        </div>
                                                        <div class="col-md-9 pl-0">
                                                            <h6 class="mb-0">{{ $item['name'] }}</h6>
                                                            <p class="mb-0">{{count($item['agents'])}} Agents : <span>1 Busy ・ 1 Inactive</span></p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>

                                        </h5>
                                    </div>

                                    <div id="collapse-{{ $item['id'] }}" class="collapse" data-parent="#accordion"
                                        aria-labelledby="heading-1">
                                        <div class="card-body">


                                            @foreach ($item['agents'] as $agent)
                                                <div id="accordion-{{ $agent['id'] }}">
                                                    <div class="card">
                                                        <div class="card-header" id="by{{ $agent['id'] }}">
                                                            <h5 class="mb-0">
                                                                <a class="profile-block collapsed" role="button"
                                                                    data-toggle="collapse" href="#collapse{{ $agent['id'] }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="collapse{{ $agent['id'] }}">
                                                                    <div class="row">
                                                                        <div class="col-md-2">
                                                                            <img class="profile-image"
                                                                                src="https://dummyimage.com/36x36/ccc/fff">
                                                                        </div>
                                                                        <div class="col-md-10">
                                                                            <h6 class="mb-0">{{ $agent['name'] }}</h6>
                                                                            <p class="mb-0">Busy ・ <span>2 Tasks</span></p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <div id="collapse{{ $agent['id'] }}" class="collapse"
                                                            data-parent="#accordion-{{ $agent['id'] }}"
                                                            aria-labelledby="by{{ $agent['id'] }}">
                                                            @foreach ($agent['order'] as $orders)
                                                                @foreach ($orders['task'] as $tasks)
                                                                    <div class="card-body">
                                                                        <div class="p-3 assigned-block">
                                                                            <div class="wd-10">
                                                                                <img class="vt-top"
                                                                                    src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                                                            </div>
                                                                            <div class="wd-90">
                                                                                <h6>{{ $tasks['location']['address'] }}</h6>
                                                                                <span>{{ $tasks['location']['short_name'] }}</span>
                                                                                <h5 class="mb-1"><span>Pickup before</span>
                                                                                    06:30 pm
                                                                                </h5>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <a class="view-task-details"
                                                                                            href="">View Task
                                                                                            Details</a>
                                                                                    </div>
                                                                                    <div class="col-md-6 text-right">
                                                                                        <button
                                                                                            class="assigned-btn">Assigned</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="map-wrapper">
                            <div style="width: 100%">
                                <div id="map_canvas" style="width: 100%; height: 800px;"></div>
                            </div>
                            <div class="contant">
                                <div class="bottom-content">
                                <input type="text" id="basic-datepicker" class="brdr-1 datetime" value="{{date('Y-m-d')}}">
                                    <div class="dropdown d-inline-block brdr-1">
                                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img class="mr-1"
                                                src="{{ asset('demo/images/ic_assigned_to.png') }}">{{ count($teams)+1 }}
                                            Teams
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

                                                <div class="row mt-2 teamchange">
                                                    <div class="col-md-8">
                                                        <h6>All Teams</h6>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <label class="">
                                                            <input class="newchecks" cla type="checkbox" value="-1"
                                                                name="teamchecks[]" checked>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 teamchange">
                                                    <div class="col-md-8">
                                                        <h6>Unassigend team</h6>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <label class="">
                                                            <input class="newchecks" cla type="checkbox" value="0"
                                                                name="teamchecks[]">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                @foreach ($teams as $item)
                                                    <div class="row mt-2 teamchange">
                                                        <div class="col-md-8">
                                                            <h6>{{ $item['name'] }}</h6>
                                                        </div>
                                                        <div class="col-md-4 text-right">
                                                            <label class="">
                                                                <input class="newchecks" type="checkbox" name="teamchecks[]"
                                                                    value="{{ $item['id'] }}">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
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
                                                        <span>Task Status </span>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <a href=""><span></span></a>
                                                        <a class="ml-3" href=""><span></span></a>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-md-8">
                                                        <h6><img class="mr-2"
                                                                src=""></span>All
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <label class="">
                                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                                value="all">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-md-8">
                                                        <h6><img class="mr-2"
                                                                src="{{ asset('demo/images/blue-dot.png') }}"></span>Unassigned
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <label class="">
                                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                                value="unassigned">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-8">
                                                        <h6><img class="mr-2"
                                                                src="{{ asset('demo/images/green-dot.png') }}"></span>Assigned
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <label class="">
                                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                                value="assigned">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-8">
                                                        <h6><img class="mr-2"
                                                                src="{{ asset('demo/images/yellow-dot.png') }}"></span>In
                                                            transit</h6>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <label class="">
                                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                                value="in-transit">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-8">
                                                        <h6><img class="mr-2"
                                                                src="{{ asset('demo/images/pink-dot.png') }}"></span>Completed
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <label class="">
                                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                                value="completed">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-9">
                                                        <h6><img class="mr-2"
                                                                src="{{ asset('demo/images/purple-dot.png') }}"></span>Failed
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-3 text-right">
                                                        <label class="">
                                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                                value="failed">
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
                                                        <a href=""><span></span></a>
                                                        <a class="ml-3" href=""><span></span></a>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-md-8">
                                                        <h6>All Drivers</h6>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <label class="">
                                                            <input class="agentdisplay" type="checkbox" name="agentcheck[]" value="2">
                                                            <span class="checkmark" ></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-8">
                                                        <h6><span class="circle lia-castro mr-2"></span>Online</h6>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <label class="">
                                                            <input class="agentdisplay" type="checkbox" name="agentcheck[]" value="1">
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
                                                            <input class="agentdisplay" type="checkbox" name="agentcheck[]" value="0">
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

        <script defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&callback=initMap&libraries=&v=weekly">
        </script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
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
        <script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
        <script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

        <!-- Page js-->
        <script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
        {{-- <script src="{{ asset('demo/js/propeller.min.js') }}"></script>
        --}}
        <script>
            $(document).ready(function() {
                $('#shortclick').trigger('click');
            });

            // var marker;
            var show = [0];
            let map;
            let markers = [];


            // $("#abc").click(function() {
            //     $(this).data('id');
            // });
            var olddata  = {!!json_encode($newmarker)!!};
            var allagent = {!!json_encode($agents)!!};
            // var teamdata = {!!json_encode($teams)!!};
            // var cars    = [0];

            $('.newchecks').click(function() {
                var val = [];
                $('.newchecks:checkbox:checked').each(function(i) {
                    val[i] = parseInt($(this).val());
                });
                setMapOnAll(null);
                $(".taskchecks").prop('checked', false);
                $(".agentdisplay").prop('checked', false);
                //   if (!$(this).is(':checked')) {
                //    return confirm("Are you sure?");
                //   }
                console.log(val);
                for (let i = 0; i < olddata.length; i++) {
                    checkdata = olddata[i];
                    var info = []
                    // addMarker({ lat: checkdata[3], lng: checkdata[4] });
                    if ($.inArray(checkdata[0], val) != -1 || $.inArray(-1, val) != -1) {
                        if (checkdata[6] == 1) {
                            send = "P";
                        } else if (checkdata[6] == 2) {
                            send = "D";
                        } else {
                            send = "A";
                        }
                        img = null;
                        type = 1;
                        addMarker({
                            lat: checkdata[3],
                            lng: checkdata[4]
                        }, send, img,checkdata,type);
                    }
                }



            });

            $('.taskchecks').click(function() {
                var taskval = [];
                $('.taskchecks:checkbox:checked').each(function(i) {
                    taskval[i] = $(this).val();

                });

                setMapOnAll(null);
                $(".newchecks").prop('checked', false);
                $(".agentdisplay").prop('checked', false); 
               //$('.taskchecks:checkbox').removeAttr('checked');
                //   if (!$(this).is(':checked')) {
                //    return confirm("Are you sure?");
                //   }
                for (let i = 0; i < olddata.length; i++) {
                    checkdata = olddata[i];
                    console.log(checkdata);
                    // addMarker({ lat: checkdata[3], lng: checkdata[4] });
                    if ($.inArray(checkdata[5], taskval) != -1 || $.inArray('all', taskval) != -1) {

                        

                        switch (checkdata[5]) {
                            
                            case "assigned":
                                image = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
                                break;
                            case "unassigned":
                                image = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
                                break;
                            case "in-transit":
                                image = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
                                break;
                            case "completed":
                                image = 'http://maps.google.com/mapfiles/ms/icons/pink-dot.png';
                                break;
                            case "failed":
                                image = 'http://maps.google.com/mapfiles/ms/icons/purple-dot.png';
                                break;
                        }
                        send = null;
                        type = 1;
                        addMarker({lat: checkdata[3],lng: checkdata[4]}, send,image,checkdata,type);
                    }
                }



            });

            $('.agentdisplay').click(function() {
                var agentval = [];
                $('.agentdisplay:checkbox:checked').each(function(i) {
                    agentval[i] = parseInt($(this).val());
                });
                setMapOnAll(null);
                $(".taskchecks").prop('checked', false);
                $(".newchecks").prop('checked', false);
                //   if (!$(this).is(':checked')) {
                //    return confirm("Are you sure?");
                //   }
                //console.log(agentval);
                var url = window.location.origin;
                
                for (let i = 0; i < allagent.length; i++) {
                    checkdata = allagent[i];
                    console.log(checkdata);
                    // addMarker({ lat: checkdata[3], lng: checkdata[4] });
                    if ($.inArray(checkdata['is_available'], agentval) != -1 || $.inArray(2, agentval) != -1) {
                        if (checkdata['is_available'] == 1) {
                            image = url+'/demo/images/online1.png';
                        }else {
                            image = url+'/demo/images/offline1.png';
                        }
                        send = null;
                        type = 2;
                       addMarker({lat: parseFloat(checkdata.agentlog['lat']),lng:  parseFloat(checkdata.agentlog['long'])}, send, image,checkdata,type);
                    }
                }



            });



            function initMap() {

                const haightAshbury = {
                    lat: 30.7046,
                    lng: 76.7179
                };

                map = new google.maps.Map(document.getElementById("map_canvas"), {
                    zoom: 12,
                    center: haightAshbury,
                    mapTypeId: "roadmap",
                });
                // This event listener will call addMarker() when the map is clicked.
                // map.addListener("click", (event) => {
                //   addMarker(event.latLng);
                // });
                // Adds a marker at the center of the map.
                for (let i = 0; i < olddata.length; i++) {
                    checkdata = olddata[i];
                    console.log(checkdata);
                    if (checkdata[6] == 1) {
                        send = "P";
                    } else if (checkdata[6] == 2) {
                        send = "D";
                    } else {
                        send = "A";
                    }
                    img = null
                    type = 1;
                    addMarker({
                        lat: checkdata[3],
                        lng: checkdata[4]
                    }, send, img,checkdata,type);
                }
            }
            
            
            // Adds a marker to the map and push to the array.
            function addMarker(location, lables, images,data,type) {
                var contentString = '';
                if(type == 1){
                    contentString =
                    '<div id="content">' +
                    '<div id="siteNotice">' +
                    "</div>" +
                    '<h5 id="firstHeading" class="firstHeading">'+data[7]+'</h5>' +
                    '<h6 id="firstHeading" class="firstHeading">'+data[11]+'</h6>' +
                    '<div id="bodyContent">' +
                    "<p><b>Address :- </b> " +data[8]+ " " +
                    ".</p>" +
                    '<p><b>Customer: '+data[9]+'</b>('+data[10]+') </p>' +
                    "</div>" +
                    "</div>";
                }else{
                    img = data['image_url'];
                    //console.log(img);
                    contentString =
                    '<div style="float:left">'+
                    '<img src="{{\Phumbor::url(\Storage::disk("s3")->url("assets/client_00000125/agents5fc76c71abdb3.png/A9B2zHkr5thbcyTKHivaYm4kNYrSXOiov6USdFpV.png"))->fitIn(90,50)}}">'+
                    "</div>"+
                    '<div style="float:right; padding: 10px;"><b>'+data['name']+'</b><br/><br/>'+data['phone_number']+'</div>';
                }
                


                const infowindow = new google.maps.InfoWindow({
                    content: contentString,
                    maxWidth: 250,
                    maxheight: 250,
                });







               
                const marker = new google.maps.Marker({
                    position: location,
                    label: lables,
                    icon: images,
                    map: map,
                    animation: google.maps.Animation.DROP,
                });
                markers.push(marker);

                marker.addListener("click", () => {
                   infowindow.open(map, marker);
                });

            }

            // Sets the map on all markers in the array.
            function setMapOnAll(map) {

                for (let i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
            }

            // Removes the markers from the map, but keeps them in the array.
            function clearMarkers() {
                setMapOnAll(null);
            }

            // Shows any markers currently in the array.
            function showMarkers() {
                setMapOnAll(map);
            }

            // Deletes all markers in the array by removing references to them.
            function deleteMarkers() {
                clearMarkers();
                markers = [];
            }


            $(".datetime").on('change', function postinput(){
               
                
                var matchvalue = $(this).val(); // this.value
                newabc =  'http://192.168.100.211:8000/'+'?date='+matchvalue;
                
                
                window.location.href = newabc;
                
            });
          

        </script>
    </body>

    </html>
@endsection
