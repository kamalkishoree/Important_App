@extends('layouts.vertical', ['title' => 'Options'])

@section('css')
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
                <h4 class="page-title">Agent</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($agent))
                    <form id="UpdateAgent" method="post" action="{{route('agent.update', $agent->id)}}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @else
                        <form id="StoreAgent" method="post" action="{{route('agent.store')}}"
                            enctype="multipart/form-data">
                            @endif
                            @csrf
                            <div class="modal-body p-4">
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <div class="form-group" id="profile_pictureInput">
                                            <input type="file" data-plugins="dropify" name="profile_picture" data-default-file="{{isset($agent->profile_picture) ? asset('agents/'.$agent->profile_picture.'') : ''}}" />
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                        <p class="text-muted text-center mt-2 mb-0">Profile Pic</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="nameInput">
                                            <label for="name" class="control-label">NAME</label>
                                            <input type="text" class="form-control" id="name" placeholder="John Doe" name="name" value="{{ old('name', $agent->name ?? '')}}">
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="phone_numberInput">
                                            <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">+91</span>
                                                </div>
                                                <input type="text" name="phone_number" class="form-control" id="phone_number"
                                                    placeholder="Enter mobile number" value="{{ old('name', $agent->phone_number ?? '')}}">
                                            </div>
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="typeInput">
                                            <label for="type" class="control-label">TYPE</label>
                                            <select class="selectpicker" data-style="btn-light" name="type" id="type">
                                                <option value="Freelancer" @if($agent->type=='Freelancer') selected @endif>Freelancer</option>
                                                <option value="Employee" @if($agent->type=='Employee') selected @endif>Employee</option>
                                                <option value="Relish" @if($agent->type=='Relish') selected @endif>Relish</option>
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="team_idInput">
                                            <label for="team_id" class="control-label">ASSIGN TEAM</label>
                                            <select class="selectpicker" data-style="btn-light" name="team_id" id="team_id">
                                                <option>Select Team</option>
                                                @foreach($teams as $team)
                                                <option value="{{$team->id}}" @if($agent->team_id == $team->id) selected @endif>{{$team->name}}</option>
                                                @endforeach
                                                <option value="other">other</option>
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" id="vehicle_type_idInput">
                                            <p class="text-muted mt-3 mb-2">TRANSPORT TYPE</p>
                                            <div class="radio radio-primary form-check-inline">
                                                <input type="radio" id="onfoot" value="onfoot" name="vehicle_type_id" @if($agent->vehicle_type_id == 'onfoot') checked @endif>
                                                <label for="onfoot"> On Foot </label>
                                            </div>
                                            <div class="radio radio-success form-check-inline">
                                                <input type="radio" id="bycycle" value="bycycle" name="vehicle_type_id" @if($agent->vehicle_type_id == 'bycycle') checked @endif>
                                                <label for="bycycle"> Bycycle </label>
                                            </div>
                                            <div class="radio radio-info form-check-inline">
                                                <input type="radio" id="motorbike" value="motorbike" name="vehicle_type_id" @if($agent->vehicle_type_id == 'motorbike') checked @endif> 
                                                <label for="motorbike"> Motor Bike </label>
                                            </div>
                                            <div class="radio radio-danger form-check-inline">
                                                <input type="radio" id="car" value="car" name="vehicle_type_id" @if($agent->vehicle_type_id == 'car') checked @endif>
                                                <label for="car"> Car </label>
                                            </div>
                                            <div class="radio radio-warning form-check-inline">
                                                <input type="radio" id="truck" value="truck" name="vehicle_type_id">
                                                <label for="truck"> Truck </label>
                                            </div>
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" id="make_modelInput">
                                            <label for="make_model" class="control-label">TRANSPORT DETAILS</label>
                                            <input type="text" class="form-control" id="make_model" placeholder="Year, Make, Model"
                                                name="make_model" value="{{ old('name', $agent->make_model ?? '')}}">
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="plate_numberInput">
                                            <label for="plate_number" class="control-label">LICENCE PLACE</label>
                                            <input type="text" class="form-control" id="plate_number" name="plate_number"
                                                placeholder="508.KLV" value="{{ old('name', $agent->plate_number ?? '')}}">
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="colorInput">
                                            <label for="color" class="control-label">COLOR</label>
                                            <input type="text" class="form-control" id="color" name="color" placeholder="Color" value="{{ old('name', $agent->color ?? '')}}">
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>

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
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>


@endsection