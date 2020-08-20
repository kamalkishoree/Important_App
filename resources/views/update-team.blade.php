@extends('layouts.vertical', ['title' => 'Options'])

@section('css')
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />


<!-- for File Upload -->

<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($team))
                    <form id="UpdateTeam" method="post" action="{{route('team.update', $team->id)}}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @else
                        <form id="StoreTeam" method="post" action="{{route('team.store')}}"
                            enctype="multipart/form-data">
                            @endif
                            @csrf

                            <div class=" row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">NAME</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', $team->name ?? '')}}" placeholder="John Doe">
                                        @if($errors->has('name'))
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
                                            @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" @if($agent->id == $team->manager_id) selected @endif >{{ $agent->name }}</option>
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
                                            @foreach($location_accuracy as $k=>$la)
                                            <option value="{{ $k }}" @if($team->location_accuracy == $k) selected @endif>{{ $la }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('location_accuracy'))
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
                                            @foreach($location_frequency as $k=>$lf)
                                            <option value="{{ $k }}" @if($team->location_frequency == $k) selected @endif>{{ $lf }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('location_frequency'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('location_frequency') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="tagsInput">
                                        <label for="tags" class="control-label">ADD TAGS</label>
                                        <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple"
                                            data-placeholder="Choose ..." name="tags[]" id="tags">
                                            @foreach($tags as $tag)
                                            <option value="{{$tag->id}}" @if(in_array($tag->id,$teamTagIds)) selected @endif>{{$tag->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
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



</div>
@endsection

@section('script')

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