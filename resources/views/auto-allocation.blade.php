@extends('layouts.vertical', ['title' => 'Auto Allocation'])

@section('css')
    <style>
        .hidden-desc {
            display: none;
        }

        .book {
            
            margin-bottom: 10px;
        }

        .font-weight-bold {
            font-weight: 700 !important;
            margin-bottom: 19px !important;
        }

        .check {
            margin-top: 20px;


        }

        .redio-all {
            margin-top: 5px;
        }

        .message {
            margin-left: 20px;
            font-weight: bold;
        }

        .header-title {
            margin-bottom: 20px;
        }

        .extra {
            display: none;
        }

        
       

    </style>
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Auto Allocation</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-11">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <form method="POST" action="{{ route('preference', Auth::user()->code) }}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card-box">
                                <h4 class="header-title">Acknowledgement Type</h4>
                                <p class="sub-header">
                                    Agent can either acknowledge the receipt of the task or accept/decline a Task based on
                                    your
                                    selection below.
                                </p>
                                {{-- @php
                                dd($preference->);
                                @endphp --}}
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                    <div class="login-form">
                                        <ul class="list-inline">
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="acknowledge1" class="autoredio" value="acknowledge"
                                                name="acknowledgement_type"
                                                {{ isset($preference) && $preference->acknowledgement_type == 'acknowledge' ? 'checked' : '' }}>
                                            <label for="acknowledge1"> Acknowledge </label>
                                            </li>
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="acknowledge2" class="autoredio" value="acceptreject"
                                                name="acknowledgement_type"
                                                {{ isset($preference) && $preference->acknowledgement_type == 'acceptreject' ? 'checked' : '' }}>
                                            <label for="acknowledge2"> Accept/Reject </label>
                                            </li>
                                            <li class="d-inline-block">
                                                <input type="radio" id="acknowledge3" class="autoredio" value="none" name="acknowledgement_type"
                                                {{ isset($preference) && $preference->acknowledgement_type == 'none' ? 'checked' : '' }}>
                                            <label for="acknowledge3"> None </label>
                                            </li>
                                          </ul>
                                        </div>
                                    </div> 
                                    {{-- <div class="col-sm-12">
                                        <p class="text-muted mb-2">SELECT PREFERENCE</p>
                                        <div class="radio radio-info form-check-inline">
                                            <input type="radio" id="acknowledge1" value="acknowledge"
                                                name="acknowledgement_type"
                                                {{ isset($preference) && $preference->acknowledgement_type == 'acknowledge' ? 'checked' : '' }}>
                                            <label for="acknowledge1"> Acknowledge </label>
                                        </div>
                                        <div class="radio form-check-inline">
                                            <input type="radio" id="acknowledge2" value="acceptreject"
                                                name="acknowledgement_type"
                                                {{ isset($preference) && $preference->acknowledgement_type == 'acceptreject' ? 'checked' : '' }}>
                                            <label for="acknowledge2"> Accept/Reject </label>
                                        </div>
                                        <div class="radio form-check-inline">
                                            <input type="radio" id="acknowledge3" value="none" name="acknowledgement_type"
                                                {{ isset($preference) && $preference->acknowledgement_type == 'none' ? 'checked' : '' }}>
                                            <label for="acknowledge3"> None </label>
                                        </div>
                                        @if ($errors->has('acknowledgement_type'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('acknowledgement_type') }}</strong>
                                            </span>
                                        @endif
                                    </div> --}}

                                </div>
                                {{-- <div class="row mb-2">
                                    <div class="col-md-2">
                                        <div class="form-group mb-0 text-center">
                                            <button class="btn btn-blue btn-block" type="submit"> Update </button>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </form>
                <form method="post" action="{{ route('auto-allocation.update', Auth::user()->code ?? '') }}" id="logic_form">
                    @csrf
                    @method('PUT')
                    <div class="card-box">
                        <h4 class="header-title">Auto Allocation</h4>
                        <div class="custom-switch redio-all">
                            <input type="checkbox" value="1" class="custom-control-input large-icon" id="manual_allocation"
                                name="manual_allocation"
                                {{ isset($allocation) && $allocation->manual_allocation == 1 ? 'checked' : '' }}>
                            <label class="custom-control-label check" for="manual_allocation"></label>
                            <p class="sub-header message">
                                Enable this option to automatically assign Task to your agent.

                            </p>
                            <div class="col-sm-4 text-right">

                                @if ($errors->has('manual_allocation'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('manual_allocation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="custom-switch redio-all">
                            <input type="checkbox" value="1" class="custom-control-input large-icon" id="self_assign"
                                name="self_assign"
                                {{ isset($allocation) && $allocation->self_assign == 1 ? 'checked' : '' }}>
                            <label class="custom-control-label check" for="self_assign"></label>
                            <p class="sub-header message">
                                Enable this option to alow self Assign.

                            </p>
                            <div class="col-sm-4 text-right">

                                @if ($errors->has('manual_allocation'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('manual_allocation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="number_of_retries">NO. OF RETRIES</label>
                                    <input type="text" name="number_of_retries" id="number_of_retries" placeholder="0"
                                        class="form-control"
                                        value="{{ isset($allocation) && $allocation->number_of_retries != null ? $allocation->number_of_retries : '' }}"
                                        require>
                                </div>
                                @if ($errors->has('number_of_retries'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('number_of_retries') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_before_task_time">START ALLOCATION BEFORE TASK TIME (IN
                                        MINUTES)</label>
                                    <input type="text" name="start_before_task_time" id="start_before_task_time"
                                        placeholder="5" class="form-control"
                                        value="{{ isset($allocation) && $allocation->start_before_task_time != null ? $allocation->start_before_task_time : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="request_expiry">REQUEST EXPIRES IN SEC</label>
                                    <input type="text" name="request_expiry" id="request_expiry" placeholder="30"
                                        class="form-control"
                                        value="{{ isset($allocation) && $allocation->request_expiry != null ? $allocation->request_expiry : '' }}"
                                        require>
                                </div>
                                @if ($errors->has('request_expiry'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('request_expiry') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="task_priority">Maximum Task Per Person</label>
                                    <input type="text" name="maximum_task_per_person" id="request_expiry" placeholder="10"
                                        class="form-control"
                                        value="{{ isset($allocation) && $allocation->maximum_task_per_person != null ? $allocation->maximum_task_per_person : '' }}"
                                        require>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="maximum_radius">Maximum Radius</label>
                                    <input type="text" name="maximum_radius" id="maximum_radius" placeholder="30"
                                        class="form-control"
                                        value="{{ isset($allocation) && $allocation->maximum_radius != null ? $allocation->maximum_radius : '' }}">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="maximum_batch_size">Maximum Batch Size</label>
                                    <input type="text" name="maximum_batch_size" id="maximum_batch_size"
                                        placeholder="10" class="form-control"
                                        value="{{ isset($allocation) && $allocation->maximum_batch_size != null ? $allocation->maximum_batch_size : '' }}">
                                </div>
                            </div>
                        </div>

                        <h4 class="header-title">Select a method to allocate task</h4>

                        <div class="row mb-2 mt-2" id="rediodiv">
                            <div class="col-md-6 click first_click five" id="redio1">
                                <div class="border p-3 rounded book ">
                                    <div class="row">
                                        <div class="col-md-8 first-part">
                                    
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="shippingMethodRadio1" name="auto_assign_logic"
                                                class="custom-control-input custom-logic" value="one_by_one"
                                                {{ isset($allocation) && $allocation->auto_assign_logic == 'one_by_one' ? 'checked' : '' }}>
                                            <label class="custom-control-label font-16 font-weight-bold lab"
                                                for="shippingMethodRadio1">One By One</label>
                                        </div>
                                        <strong class="tagline one_by_one" style="">Allocation will done one by one</strong>
                                    </div>
                                    <div class="col-md-4 icon-part">
                                    <img class="img-fluid" src="{{asset('assets/icons/onebyone.png')}}"  alt="">
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 click five" id="redio2">
                                <div class="border p-3 rounded book">
                                    <div class="row">
                                    <div class="col-md-8 first-part">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="shippingMethodRadio2" name="auto_assign_logic"
                                                class="custom-control-input custom-logic" value="send_to_all"
                                                {{ isset($allocation) && $allocation->auto_assign_logic == 'send_to_all' ? 'checked' : '' }}>
                                            <label class="custom-control-label font-16 font-weight-bold lab"
                                                for="shippingMethodRadio2">Send to all</label>
                                        </div>
                                        <strong class="tagline send_to_all">Allocation request will send to all</strong>
                                    </div>
                                    <div class="col-md-4 icon-part">
                                        <img class="img-fluid" src="{{asset('assets/icons/sendtoall.png')}}"  alt="">
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-6 click batch" id="redio3">
                                <div class="border p-3 rounded book">
                                    <div class="row">
                                        <div class="col-md-8 first-part">
                                
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="shippingMethodRadio3" name="auto_assign_logic"
                                                class="custom-control-input custom-logic" value="batch_wise"
                                                {{ isset($allocation) && $allocation->auto_assign_logic == 'batch_wise' ? 'checked' : '' }}>
                                            <label class="custom-control-label font-16 font-weight-bold lab"
                                                for="shippingMethodRadio3">Batch Wise</label>
                                        </div>
                                        <strong class="tagline batch_wise">Allocation request will done batch wise</strong>
                                    </div>
                                    <div class="col-md-4 icon-part">
                                        <img class="img-fluid" src="{{asset('assets/icons/batch.png')}}"  alt="">
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 click five" id="redio4">
                                <div class="border p-3 rounded book set">
                                    <div class="row">
                                        <div class="col-md-8 first-part">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="shippingMethodRadio4" name="auto_assign_logic"
                                                class="custom-control-input custom-logic" value="round_robin"
                                                {{ isset($allocation) && $allocation->auto_assign_logic == 'round_robin' ? 'checked' : '' }}>
                                            <label class="custom-control-label font-16 font-weight-bold lab"
                                                for="shippingMethodRadio4">Round Robin</label>
                                        </div>
                                        <strong class="tagline round_robin">Allocation request will done in round robin
                                            format</strong>
                                    </div>
                                    <div class="col-md-4 icon-part">
                                        <img class="img-fluid" src="{{asset('assets/icons/roundrobin.png')}}"  alt="">
                                    </div>
                                    </div>
                                </div>
                                <div class="abc">

                                </div>
                            </div>
                            {{-- <div class="col-md-4 click five" id="redio5">
                                <div class="border p-3 rounded book">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="shippingMethodRadio5" name="auto_assign_logic"
                                            class="custom-control-input custom-logic" value="nearest_available"
                                            {{ isset($allocation) && $allocation->auto_assign_logic == 'nearest_available' ? 'checked' : '' }}>
                                        <label class="custom-control-label font-16 font-weight-bold lab"
                                            for="shippingMethodRadio5">Nearest Available</label>
                                    </div>
                                    <strong class="tagline nearest_available" id="redio5">Allocation request will send to
                                        nearst
                                        available</strong>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-4 click five" id="redio6">
                                <div class="border p-3 rounded book">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="shippingMethodRadio6" name="auto_assign_logic"
                                            class="custom-control-input custom-logic" value="first_in_first_out"
                                            {{ isset($allocation) && $allocation->auto_assign_logic == 'first_in_first_out' ? 'checked' : '' }}>
                                        <label class="custom-control-label font-16 font-weight-bold lab"
                                            for="shippingMethodRadio6">First In, First Out</label>
                                    </div>
                                    <strong class="tagline first_in_first_out">Allocation request will send on the basic of
                                        first in first
                                        out</strong>
                                </div>
                            </div> --}}
                            @if ($errors->has('auto_assign_logic'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('auto_assign_logic') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="extra">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="start_radius">Start Radius</label>
                                        <input type="text" name="start_radius" id="start_radius" placeholder="0"
                                            class="form-control"
                                            value="{{ isset($allocation) && $allocation->start_radius != null ? $allocation->start_radius : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="increment_radius">Increment Radius</label>
                                        <input type="text" name="increment_radius" id="increment_radius" placeholder="5"
                                            class="form-control"
                                            value="{{ isset($allocation) && $allocation->increment_radius != null ? $allocation->increment_radius : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-blue btn-block" type="submit"> Update </button>
                                </div>
                            </div>
                        </div>


                    </div>

                </form>
            </div>
        </div>
    </div>





@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // jQuery(function() {
            //     jQuery('#redio1').click();
            // });






            $(function() {
                $('#manual_allocation').change(function() {
                    var checked = $('#manual_allocation').prop('checked');
                    if (checked) {
                        $('.custom-logic').attr('disabled', false);
                    } else {
                        $('.custom-logic').attr('disabled', true);
                    }
                });
            });
            $('.click').click(function() {

                $(this).find('input[type="radio"]').prop('checked', true);



            });

            var simple = '{{ isset($allocation->auto_assign_logic) ? $allocation->auto_assign_logic :'' }}';
            if(simple === 'batch_wise'){
                $('.extra').show();

            }

            $('.batch').click(function() {

                $('.extra').show();

            });
            $('.five').click(function() {

                $('.extra').hide();

            });

            var CSRF_TOKEN = $("input[name=_token]").val();
            
            
            $(document).on('click', '.autoredio', function () {
                var value = $("input[name='acknowledgement_type']:checked").val();
               
            $.ajax({
                url: "{{ route('auto-update', Auth::user()->code ?? '') }}",
                type: 'PATCH',
                data: { _token: CSRF_TOKEN,acknowledgement_type: value,_method: "PATCH"},
                success: function(res) {

                }
            });
          });



        });

    </script>
@endsection
