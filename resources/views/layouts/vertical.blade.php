<!DOCTYPE html>
    <html lang="en">

    <head>
        @include('layouts.shared/title-meta', ['title' => $title])
        @include('layouts.shared/head-css', ["demo" => "creative"])

        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="{{asset('assets/js/vendor.min.js')}}"></script>
</head>

    <body @yield('body-extra')>
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.shared/topbar')

            
            @include('layouts.shared/left-sidebar')




        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">                                    
            <div class="content">
                @php 
                    $style = "";
                    if(session('preferences.twilio_status') != 'invalid_key'){
                        $style = "display:none;";
                    }
                @endphp

                <div class="row displaySettingsError" style="{{$style}}">
                    <div class="col-12">
                        <div class="alert alert-danger excetion_keys" role="alert">
                            @if(session('preferences.twilio_status') == 'invalid_key')
                            <span><i class="mdi mdi-block-helper mr-2"></i> <strong>Twilio</strong> key is not valid</span> <br/>
                            @endif
                        </div>
                    </div>


                </div>


                @yield('content')
            </div>
            <!-- content -->


            @include('layouts.shared/footer')

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    @include('layouts.shared/right-sidebar')
    @include('layouts.shared/add-task-head')

    @include('layouts.shared/footer-script')
        
    </body>

</html>