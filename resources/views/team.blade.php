@extends('layouts.vertical', ['title' => 'Geo Fence'])

@section('css')
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />


<!-- for File Upload -->

<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@include('modals.add-team')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-4">
            <div class="card-box">
                <div class="dropdown float-right">
                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        
                        <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                        
                        <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                        
                        <a href="javascript:void(0);" class="dropdown-item">Action</a>
                    </div>
                </div>

                <h4 class="header-title mb-3">Teams </h4>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-toggle="modal"
                        data-target="#add-team-modal" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Team</button>

                <div class="table-responsive">
                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                        <thead class="thead-light">
                            <tr>
                                <th>Team Name</th>
                                <th>Manager</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                            <tr class="team-list-1" data-id="{{ $team->id }}">
                                <td>
                                    <h5 class="m-0 font-weight-normal">{{ $team->name }}</h5>
                                </td>

                                <td>
                                    {{ $team->manager ? $team->manager->name : '' }}
                                </td>


                                <td>
                                    <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div> <!-- end .table-responsive-->
            </div> <!-- end card-box-->
        </div>

        @foreach($teams as $index=>$team)
        <div class="col-xl-4 team-details" id="team_detail_{{ $team->id}}" @if($index!=0) style="display:none;"  @endif>
            <div class="card-box">

                <h4 class="header-title mb-3">Team Deatail</h4>
                <p>ID : <strong>{{ $team->id }}</strong></p>
                <h3>{{ $team->name }}</h3>
                <p>Manager : <strong>{{ $team->manager ? $team->manager->name : '' }}</strong></p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-6">
                            <h5>Location Accuracy</h5>
                            <p>{{ $team->location_accuracy }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-6">
                            <h5>Location Frequency</h5>
                            <p>{{ $team->location_frequency }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Tags</h5>
                    <div class="text-uppercase">
                        @foreach($team->tags as $tag)
                        <a href="#" class="badge badge-soft-primary mr-1">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div> <!-- end card-box-->
        </div>
        @endforeach

        @foreach($teams as $index=>$team)
        <div class="col-xl-4 team-agent-list" id="team_agents_{{ $team->id}}" @if($index!=0) style="display:none;"  @endif>
            <div class="card-box">
                <div class="dropdown float-right">
                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        
                        <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                        
                        <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                        
                        <a href="javascript:void(0);" class="dropdown-item">Action</a>
                    </div>
                </div>

                <h4 class="header-title mb-3">Agents</h4>

                <div class="table-responsive">
                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                        <thead class="thead-light">
                            <tr>
                                <th>Team Name</th>
                                <th>Manager</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($team->agents as $agent)
                            <tr>
                                <td>
                                    <h5 class="m-0 font-weight-normal">{{ $agent->name }}</h5>
                                </td>

                                <td>
                                    {{ $agent->manager ? $agent->manager->name : '' }}
                                </td>


                                <td>
                                    <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div> <!-- end .table-responsive-->
            </div> <!-- end card-box-->
        </div>
        @endforeach    
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card-box">
                <div class="dropdown float-right">
                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        
                        <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                        
                        <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                        
                        <a href="javascript:void(0);" class="dropdown-item">Action</a>
                    </div>
                </div>

                <h4 class="header-title mb-3">Agents</h4>

                <div class="table-responsive">
                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                        <thead class="thead-light">
                            <tr>
                                <th>Agent Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Team Name</th>
                                <th>Manager</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agents as $agent)
                            <tr>
                                <td>
                                    <h5 class="m-0 font-weight-normal">{{ $agent->name }}</h5>
                                </td>

                                <td>
                                    {{ $agent->email }}
                                </td>

                                <td>
                                    {{ $agent->phone_number }}
                                </td>

                                <td>
                                    {{ $agent->team->manager->name }}
                                </td>

                                <td>
                                    asdf
                                </td>

                                <td>
                                    <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div> <!-- end .table-responsive-->
            </div> <!-- end card-box-->
        </div> 
    </div>
</div>
@endsection

@section('script')
<script>
$( ".team-list-1" ).click(function() {
  var data_id = $(this).attr('data-id');
  $(".team-details").hide();
  $("#team_detail_"+data_id).show();

  $(".team-agent-list").hide();
  $("#team_agents_"+data_id).show();
});
</script>
<!-- Plugins js-->
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>

<script src="{{asset('assets/js/storeTeam.js')}}"></script>

<!-- for File Upload -->
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>


@endsection