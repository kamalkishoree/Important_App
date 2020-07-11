
@extends('layouts.vertical', ['title' => 'Options'])

@section('css')
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Settings</h4>
                </div>
            </div>
        </div>     
        <!-- end page title --> 
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Options</h4>
                    <p class="sub-header">
                        Select whether you want to allow feedback on tracking URL.         
                    </p>
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                <h4 class="header-title">Allow Feedback on Tracking URL</h4>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" checked="" class="custom-control-input" id="allow_tracking_url">
                                <label class="custom-control-label" for="allow_tracking_url"></label>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
        
        
    </div>
@endsection

@section('script')
@endsection