@extends('layouts.vertical', ['title' => 'Auto Allocation'])

@section('css')
    <style>
        .hidden-desc {
            display: none;
        }

        .book {
            height: 100px !important;
            margin-bottom: 10px;
        }

        .font-weight-bold {
            font-weight: 700 !important;
            margin-bottom: 19px !important;
        }

        .tagline {
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
                    <h4 class="page-title">Settings</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
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
                <form method="POST" action="{{ route('preference', 1) }}">
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
                                <div class="row mb-2">
                                    <div class="col-sm-12">
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
                        </div>
                    </div>
                </form>
                <form method="post" action="{{ route('auto-allocation.update', 1 ?? '') }}" id="logic_form">
                    @csrf
                    @method('PUT')
                    <div class="card-box">
                        <h4 class="header-title">Auto Allocation</h4>
                        <div class="custom-switch">
                            <input type="checkbox" value="y" class="custom-control-input large-icon" id="manual_allocation"
                                name="manual_allocation"
                                {{ isset($allocation) && $allocation->manual_allocation == 'y' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="manual_allocation"></label>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <div class="text-sm-left">
                                    <p class="sub-header">
                                        Enable this option to automatically assign Task to your agent.

                                    </p>

                                </div>
                            </div>
                            <div class="col-sm-4 text-right">

                                @if ($errors->has('manual_allocation'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('manual_allocation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <h4 class="header-title">Select a method to allocate task</h4>

                        <div class="row mb-2 mt-2" id="rediodiv">
                            <div class="col-md-4 click first_click" id="redio1">
                                <div class="border p-3 rounded book">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="shippingMethodRadio1" name="auto_assign_logic"
                                            class="custom-control-input custom-logic" value="one_by_one"
                                            {{ isset($allocation) && $allocation->auto_assign_logic == 'one_by_one' ? 'checked' : '' }}>
                                        <label class="custom-control-label font-16 font-weight-bold lab"
                                            for="shippingMethodRadio1">One By One</label>
                                    </div>
                                    <strong class="tagline one_by_one"  style="" >Allocation will done one by one</strong>
                                </div>
                            </div>
                            <div class="col-md-4 click" id="redio2">
                                <div class="border p-3 rounded book ">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="shippingMethodRadio2" name="auto_assign_logic"
                                            class="custom-control-input custom-logic" value="send_to_all"
                                            {{ isset($allocation) && $allocation->auto_assign_logic == 'send_to_all' ? 'checked' : '' }}>
                                        <label class="custom-control-label font-16 font-weight-bold lab"
                                            for="shippingMethodRadio2">Send to all</label>
                                    </div>
                                    <strong class="tagline send_to_all" >Allocation request will send to all</strong>
                                </div>
                            </div>
                            <div class="col-md-4 click" id="redio3">
                                <div class="border p-3 rounded book">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="shippingMethodRadio3" name="auto_assign_logic"
                                            class="custom-control-input custom-logic" value="batch_wise"
                                            {{ isset($allocation) && $allocation->auto_assign_logic == 'batch_wise' ? 'checked' : '' }}>
                                        <label class="custom-control-label font-16 font-weight-bold lab"
                                            for="shippingMethodRadio3">Batch Wise</label>
                                    </div>
                                    <strong class="tagline batch_wise">Allocation request will done batch wise</strong>
                                </div>
                            </div>
                            <div class="col-md-4 click" id="redio4">
                                <div class="border p-3 rounded book">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="shippingMethodRadio4" name="auto_assign_logic"
                                            class="custom-control-input custom-logic" value="round_robin"
                                            {{ isset($allocation) && $allocation->auto_assign_logic == 'round_robin' ? 'checked' : '' }}>
                                        <label class="custom-control-label font-16 font-weight-bold lab"
                                            for="shippingMethodRadio4">Round Robin</label>
                                    </div>
                                    <strong class="tagline round_robin">Allocation request will done in round robin format</strong>
                                </div>
                            </div>
                            <div class="col-md-4 click" id="redio5">
                                <div class="border p-3 rounded book">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="shippingMethodRadio5" name="auto_assign_logic"
                                            class="custom-control-input custom-logic" value="nearest_available"
                                            {{ isset($allocation) && $allocation->auto_assign_logic == 'nearest_available' ? 'checked' : '' }}>
                                        <label class="custom-control-label font-16 font-weight-bold lab"
                                            for="shippingMethodRadio5">Nearest Available</label>
                                    </div>
                                    <strong class="tagline nearest_available" id="redio5">Allocation request will send to nearst
                                        available</strong>
                                </div>
                            </div>
                            <div class="col-md-4 click" id="redio6">
                                <div class="border p-3 rounded book">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="shippingMethodRadio6" name="auto_assign_logic"
                                            class="custom-control-input custom-logic" value="first_in_first_out"
                                            {{ isset($allocation) && $allocation->auto_assign_logic == 'first_in_first_out' ? 'checked' : '' }}>
                                        <label class="custom-control-label font-16 font-weight-bold lab"
                                            for="shippingMethodRadio6">First In, First Out</label>
                                    </div>
                                    <strong class="tagline first_in_first_out">Allocation request will send on the basic of first in first
                                        out</strong>
                                </div>
                            </div>
                            @if ($errors->has('auto_assign_logic'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('auto_assign_logic') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="task_priority">TASK ALLOCATION PRIORITY</label>
                                    <select class="form-control" id="task_priority" name="task_priority" require>
                                        <option value="default">Default</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="request_expiry">REQUEST EXPIRES IN SEC</label>
                                    <input type="text" name="request_expiry" id="request_expiry" placeholder="30"
                                        class="form-control" value="" require>
                                </div>
                                @if ($errors->has('request_expiry'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('request_expiry') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="number_of_retries">NO. OF RETRIES</label>
                                    <input type="text" name="number_of_retries" id="number_of_retries" placeholder="0"
                                        class="form-control" value="" require>
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
                                        placeholder="0" class="form-control" value="">
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




            $('.detail-desc').hide();
            $('#' + '{{ isset($allocation) && $allocation->auto_assign_logic }}').show();

            $(function() {
                $('.custom-logic').change(function() {
                    $('.detail-desc').hide();
                    $('#' + $(this).val()).show();
                });
            });

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
                $('.tagline').hide();
                $(this).find('input[type="radio"]').prop('checked', true);

                $(this).find('.tagline').show();


            });

            var form = document.getElementById("logic_form");
            var classname = form.elements["auto_assign_logic"].value;
            var check = '.'+classname;
            $(check).show();


        });

    </script>
@endsection
