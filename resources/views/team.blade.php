@extends('layouts.vertical', ['title' => 'Team'])

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />


    <style>
        // workaround
        .intl-tel-input {
            display: table-cell;
        }

        .inner-div {
            width: 50%;
            float: left;
        }

        .intl-tel-input .selected-flag {
            z-index: 4;
        }

        .intl-tel-input .country-list {
            z-index: 5;
        }

        .input-group .intl-tel-input .form-control {
            border-top-left-radius: 4px;
            border-top-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 0;
        }

        .cursors {
            cursor: move;
        }

        .label-info {
            background-color: #5bc0de;
            display: inline-block;
            padding: 0.2em 0.6em 0.3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25em;
        }

        .containers {
            margin: 20px;
        }

        /* autocomplete tagsinput*/
        .label-info {
            background-color: #5bc0de;
            display: inline-block;
            padding: 0.2em 0.6em 0.3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25em;
        }

    </style>
@endsection

@section('content')
    @include('modals.add-team')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Team</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <div class="text-sm-left">
                                    @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <span>{!! \Session::get('success') !!}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <a href="{{ route('team.create') }}"><button type="button" class="btn btn-blue waves-effect waves-ligh"><i
                                        class="mdi mdi-plus-circle mr-1"></i> Add Team</button></a>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="teams-datatable">
                                <thead>
                                    <tr>
                                        <th>Team Name</th>
                                        <th>Location Accuracy</th>
                                        <th>Location Frequency</th>
                                        <th>Tags</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teams as $team)

                                        <tr class="team-list-1 cursors" data-id="{{ $team->id }}">
                                            <td class="table-user">
                                                <a href="javascript:void(0);"
                                                    class="text-body font-weight-semibold">{{ $team->name }}</a>
                                            </td>
                                            <td>
                                                {{ $team->location_accuracy }}
                                            </td>

                                            <td>
                                                {{ $team->location_frequency }}


                                            </td>
                                            <td>
                                                 @php
                                                     $tagname = [];
                                                
                                                 foreach ($team->tags as $item){
                                                    array_push($tagname,$item->name);
                                                 }
                                                 @endphp

                                                 {{ $List = implode(' , ', $tagname) }}
                                              
                                               
                                            </td>

                                            <td>
                                                <div class="form-ul" style="width: 60px;">
                                                    <div class="inner-div"> <a href="{{ route('team.edit', $team->id) }}"
                                                            class="action-icon"> <i
                                                                class="mdi mdi-square-edit-outline"></i></a>
                                                    </div>
                                                    <div class="inner-div">
                                                        <form method="POST" action="{{ route('team.destroy', $team->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="form-group">
                                                                <button type="submit"
                                                                    class="btn btn-primary-outline action-icon"> <i
                                                                        class="mdi mdi-delete"></i></button>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                        </div>
                        @endforeach
                        </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->





        @foreach ($teams as $index => $team)
            <div class="col-xl-4 team-agent-list" id="team_agents_{{ $team->id }}" @if ($index != 0) style="display:none;"
        @endif>
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
                        @foreach ($team->agents as $agent)
                            <tr>
                                <td>
                                    <h5 class="m-0 font-weight-normal">{{ $agent->name }}</h5>
                                </td>


                                <td>
                                    <form method="POST"
                                        action="{{ route('team.agent.destroy', ['team_id' => $agent->team_id, 'agent_id' => $agent->id]) }}"
                                        class="delete-team-agent-form">
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

    


    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>

    <script>
        $(function() {

            $('#form-tags-4').tagsInput({
                'autocomplete': {
                    source: [
                        'apple',
                        'banana',
                        'orange',
                        'pizza'
                    ]
                }
            })


        });

    </script>


    <!-- Page js-->

    <script>
        $(".team-list-1").click(function() {
            var data_id = $(this).attr('data-id');
            $(".team-details").hide();
            $("#team_detail_" + data_id).show();

            $(".team-agent-list").hide();
            $("#team_agents_" + data_id).show();
        });


        $(".tag1").click(function() {
            var val = $(this).text();

            var selectElement = $('#teamtag').eq(0);
            var selectize = selectElement.data('selectize');
            selectize.additem(1, 2);
        });

        $('.delete-team-form').on('submit', function() {
            team_agent_count = $(this).attr('data-team-agent-count');
            if (team_agent_count > 0) {
                alert("Please assign other team to agents linked to this team before deleting");
                return false;
            }
            delete_team_confirmation = confirm("Do you want to delete the team?");
            if (delete_team_confirmation === true) {
                return true;
            }
            return false;
        });

    </script>

   

@endsection
