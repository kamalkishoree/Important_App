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
@include('modals.update-team')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-4">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="header-title mb-3">Teams </h4>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-toggle="modal"
                            data-target="#add-team-modal" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Team</button>
                    </div>
                </div>

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
                                    <!-- <a href="{{route('team.edit', $team->id)}}" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a> -->
                                
                                <form method="POST" action="{{route('team.destroy', $team->id)}}" class="delete-team-form" data-team-agent-count="{{ $team->agents->count() }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary-outline action-icon"> <i
                                                class="mdi mdi-delete"></i></button>

                                    </div>
                                </form>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div> <!-- end .table-responsive-->
                <div>{{ $teams->links() }}</div>
            </div> <!-- end card-box-->
        </div>

        @foreach($teams as $index=>$team)
        <div class="col-xl-4 team-details" id="team_detail_{{ $team->id}}" @if($index!=0) style="display:none;"  @endif>
            <div class="card-box">

                <h4 class="header-title mb-3">Team Detail</h4>
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

                <div>
                    <center>
                    <button type="button" class="btn btn-danger waves-effect waves-light open_edit_modal" data-toggle="modal"
                            data-target="#edit-team-modal" data-backdrop="static" data-keyboard="false"
                            data-id="{{ $team->id }}"
                            data-name="{{ $team->name}}" 
                            data-manager-id="{{ $team->manager_id }}"
                            data-location-accuracy="{{ $team->location_accuracy}}"
                            data-location-frequency="{{ $team->location_frequency }}"
                            data-tags="{{ $team->tags()->pluck('tag_id') }}"
                            data-url="{{ route('team.update',$team->id) }}"
                            ><i class="mdi mdi-plus-circle mr-1"></i> Edit Team</button>
                    </center>
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

                <h4 class="header-title mb-3">{{ auth()->user()->getPreference->agent_name ?? 'Agents' }}</h4>

                <div class="table-responsive">
                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
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
                                <form method="POST" action="{{route('team.agent.destroy',['team_id'=>$agent->team_id,'agent_id'=> $agent->id ])}}" class="delete-team-agent-form" >
                                    @csrf
                                    @method('DELETE')
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary-outline action-icon"> <i
                                                class="mdi mdi-delete"></i></button>

                                    </div>
                                </form>
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

$('.delete-team-form').on('submit', function() {
    team_agent_count = $(this).attr('data-team-agent-count');
    if(team_agent_count > 0){
        alert("Please assign other team to agents linked to this team before deleting");
        return false;
    }
    delete_team_confirmation = confirm("Do you want to delete the team?");
    if(delete_team_confirmation === true){
        return true;
    }
    return false;
});

$(document).on('click','.open_edit_modal',function(){
    var url = $(this).attr('data-url');
    var team_id = $(this).attr('data-id');
    var name    = $(this).attr('data-name');
    var manager_id = $(this).attr('data-manager-id');
    var location_accuracy = $(this).attr('data-location-accuracy');
    var location_frequency= $(this).attr('data-location-frequency');
    var tags= $(this).attr('data-tags');
    var tagsArray = JSON.parse(tags);

    // set the values in the edit-modal //
    $('#updateTeam').attr('action',url);
    $('#updateTeam').find('input[name=name]').val(name);
    $('#updateTeam').find('select[name=manager_id]').val(manager_id);
    $('#updateTeam').find('select[name=location_accuracy]').val(location_accuracy);
    $('#updateTeam').find('select[name=location_frequency]').val(location_frequency);

    $.each(tagsArray, function(i,e){
        $("#tagsUpdate option[value='" + e + "']").prop("selected", true);
    });
    $('#tagsUpdate').trigger('change');

});

</script>

<!-- Plugins js-->
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
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