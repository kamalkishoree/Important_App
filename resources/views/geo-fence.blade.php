
@extends('layouts.vertical', ['title' => 'Geo Fence'])

@section('css')
    <link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
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
                                <label for="team">Team</label>
                                <select class="form-control" id="team">
                                    <option>No Team Selected</option>
                                    
                                </select>
                            </div>
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
@endsection