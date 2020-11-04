@extends('layouts.vertical', ['title' => 'Nestable List'])

@section('css')
    <!-- Plugins css -->
    <link href="{{asset('assets/libs/nestable2/nestable2.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                   
                    <h4 class="page-title">Pricing Rules</h4>
                </div>
            </div>
        </div>     
        <!-- end page title --> 

        
        <!-- End row -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="header-title">Details</h4>
                            <p class="sub-header">
                                
                            </p>

                            <div class="custom-dd dd" id="nestable_list_1">
                                
                            </div>
                        </div><!-- end col -->

                        <div class="col-md-6">
                            <h4 class="header-title mt-3 mt-md-0">Price Priority</h4>
                            <p class="sub-header">
                                
                            </p>

                            <div class="custom-dd dd" id="nestable_list_2">
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="11">
                                        <div class="dd-handle">
                                            Driver Tag
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="12">
                                        <div class="dd-handle">
                                            Team Tag
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="13">
                                        <div class="dd-handle">
                                            GeoFance
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div> <!-- end card-box -->
            </div> <!-- end col -->
        </div>
        <!-- end Row -->

        <div class="row">
            
        </div>
        <!-- end Row -->
        
    </div> <!-- container -->
@endsection

@section('script')
    <!-- Plugins js-->
    <script src="{{asset('assets/libs/nestable2/nestable2.min.js')}}"></script>

    <!-- Page js-->
    <script src="{{asset('assets/js/pages/nestable.init.js')}}"></script>
@endsection