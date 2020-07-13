@extends('layouts.vertical', ['title' => 'Geo Fence'])

@section('css')
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/multiselect/multiselect.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/selectize/selectize.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Settings</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="card-box">
                <h4 class="header-title mb-3">Add Geo Fence</h4>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" placeholder="ABC Deliveries" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="Description">Description (Optional)</label>
                            <textarea class="form-control" id="Description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Team</label> <br />
                            <select id="selectize-select">
                                <option data-display="Select">No Team Selected</option>
                                <option value="1">Some option</option>
                                <option value="4">Potato</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkmeout0">
                                <label class="custom-control-label" for="checkmeout0">All Agents</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>AGENT(S)</label>
                            <select id="selectize-optgroup" multiple placeholder="Select Agents">
                                <option value="">Select gear...</option>
                                <option value="pitons">Kay Tolteben</option>
                                <option value="cams">Marti Velecia</option>
                                <option value="nuts">Roger</option>
                                <option value="bolts">Garry</option>
                                <option value="stoppers">Stoppers</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button"
                            class="btn btn-block btn-outline-primary waves-effect waves-light">Cancel</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-block btn-primary waves-effect waves-light">Save</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-7">
            <div class="card-box">
                <h4 class="header-title mb-3">Basic</h4>
                <div id="gmaps-basic" class="gmaps"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- google maps api -->
<script src="https://maps.google.com/maps/api/js?key=AIzaSyDsucrEdmswqYrw0f6ej3bf4M4suDeRgNA"></script>

<!-- Plugins js-->
<script src="{{asset('assets/libs/gmaps/gmaps.min.js')}}"></script>

<!-- Page js-->
<script src="{{asset('assets/js/pages/google-maps.init.js')}}"></script>
<!-- Plugins js-->
<script src="{{asset('assets/libs/selectize/selectize.min.js')}}"></script>
<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script src="{{asset('assets/libs/multiselect/multiselect.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js')}}"></script>
<script src="{{asset('assets/libs/jquery-mockjax/jquery-mockjax.min.js')}}"></script>

<!-- Page js-->
<script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>
@endsection