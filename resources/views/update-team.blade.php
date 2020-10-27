@extends('layouts.vertical', ['title' => 'Advanced Plugins'])

@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Team</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if (isset($team))
                            <form id="UpdateTeam" method="post" action="{{ route('team.update', $team->id) }}"
                                enctype="multipart/form-data">
                                @method('PUT')
                            @else
                                <form id="StoreTeam" method="post" action="{{ route('team.store') }}"
                                    enctype="multipart/form-data">
                        @endif
                        @csrf

                        <div class=" row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">NAME</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ old('name', $team->name ?? '') }}" placeholder="John Doe">
                                    @if ($errors->has('name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group" id="manager_idInput">
                                    <label for="team-manager">Manager</label>
                                    <select class="form-control" id="team-manager" name="manager_id">
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}" @if ($agent->id == $team->manager_id) selected
                                        @endif >{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" id="location_accuracyInput">
                                    <label for="location_accuracy" class="control-label">Location Accuracy</label>
                                    <select class="form-control" id="location_accuracy" name="location_accuracy">
                                        @foreach ($location_accuracy as $k => $la)
                                            <option value="{{ $k }}" @if ($team->location_accuracy == $k) selected
                                        @endif>{{ $la }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('location_accuracy'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('location_accuracy') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="location_frequencyInput">
                                    <label for="location_frequency" class="control-label">Location Frequency</label>
                                    <select class="form-control" id="location_frequency" name="location_frequency">
                                        @foreach ($location_frequency as $k => $lf)
                                            <option value="{{ $k }}" @if ($team->location_frequency == $k) selected
                                        @endif>{{ $lf }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('location_frequency'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('location_frequency') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="control-label">Tags</label>
                                    <input type="text" class="selectize-close-btn" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                            </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div> <!-- container -->
@endsection

@section('script')
    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/multiselect/multiselect.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-mockjax/jquery-mockjax.min.js') }}"></script>

    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
@endsection
