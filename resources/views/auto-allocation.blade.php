@extends('layouts.vertical', ['title' => 'Auto Allocation'])

@section('css')
<style>
.hidden-desc {
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
    <!-- end page title -->
    <div class="row">
        <div class="col-xl-11 col-md-offset-1">
            <form method="POST" action="{{route('preference', Auth::user()->id)}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <h4 class="header-title">Acknowledgement Type</h4>
                            <p class="sub-header">
                                Agent can either acknowledge the receipt of the task or accept/decline a Task based on your
                                selection below.
                            </p>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <p class="text-muted mb-2">SELECT PREFERENCE</p>
                                    <div class="radio radio-info form-check-inline">
                                        <input type="radio" id="acknowledge1" value="acknowledge" name="acknowledgement_type"
                                            {{ ($preference->acknowledgement_type =="acknowledge")? "checked" : "" }}>
                                        <label for="acknowledge1"> Acknowledge </label>
                                    </div>
                                    <div class="radio form-check-inline">
                                        <input type="radio" id="acknowledge2" value="acceptreject" name="acknowledgement_type"
                                            {{ ($preference->acknowledgement_type =="acceptreject")? "checked" : "" }}>
                                        <label for="acknowledge2"> Accept/Reject </label>
                                    </div>
                                    <div class="radio form-check-inline">
                                        <input type="radio" id="acknowledge3" value="none" name="acknowledgement_type"
                                            {{ ($preference->acknowledgement_type =="none")? "checked" : "" }}>
                                        <label for="acknowledge3"> None </label>
                                    </div>
                                    @if($errors->has('acknowledgement_type'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('acknowledgement_type') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block" type="submit"> Update </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <form method="post" action="{{route('auto-allocation.update', Auth::user()->id ?? '')}}">
                @csrf
                @method('PUT')
                <div class="card-box">
                    <h4 class="header-title">Options</h4>
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                <p class="sub-header">
                                    Enable this option to automatically assign Task to your agent.
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" value="y" class="custom-control-input" id="manual_allocation"
                                    name="manual_allocation"
                                    {{ (isset($allocation) && $allocation->manual_allocation == "y" )? "checked" : "" }}>
                                <label class="custom-control-label" for="manual_allocation"></label>
                            </div>
                            @if($errors->has('manual_allocation'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('manual_allocation') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <h4 class="header-title">Select a method to allocate task</h4>

                    <div class="row mb-2 mt-2">
                        <div class="col-md-4">
                            <div class="border p-3 rounded mb-3">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio1" name="auto_assign_logic"
                                        class="custom-control-input custom-logic" value="one_by_one"
                                        {{ (isset($allocation) && $allocation->auto_assign_logic == "one_by_one" )? "checked" : "" }}>
                                    <label class="custom-control-label font-16 font-weight-bold"
                                        for="shippingMethodRadio1">One By One</label>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio2" name="auto_assign_logic"
                                        class="custom-control-input custom-logic" value="send_to_all"
                                        {{ (isset($allocation) && $allocation->auto_assign_logic == "send_to_all" )? "checked" : "" }}>
                                    <label class="custom-control-label font-16 font-weight-bold"
                                        for="shippingMethodRadio2">Send to all</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio3" name="auto_assign_logic"
                                        class="custom-control-input custom-logic" value="batch_wise"
                                        {{ (isset($allocation) && $allocation->auto_assign_logic == "batch_wise" )? "checked" : "" }}>
                                    <label class="custom-control-label font-16 font-weight-bold"
                                        for="shippingMethodRadio3">Batch Wise</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio4" name="auto_assign_logic"
                                        class="custom-control-input custom-logic" value="round_robin"
                                        {{ (isset($allocation) && $allocation->auto_assign_logic == "round_robin" )? "checked" : "" }}>
                                    <label class="custom-control-label font-16 font-weight-bold"
                                        for="shippingMethodRadio4">Round Robin</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio5" name="auto_assign_logic"
                                        class="custom-control-input custom-logic" value="nearest_available"
                                        {{ (isset($allocation) && $allocation->auto_assign_logic == "nearest_available" )? "checked" : "" }}>
                                    <label class="custom-control-label font-16 font-weight-bold"
                                        for="shippingMethodRadio5">Nearest Available</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shippingMethodRadio6" name="auto_assign_logic"
                                        class="custom-control-input custom-logic" value="first_in_first_out"
                                        {{ (isset($allocation) && $allocation->auto_assign_logic == "first_in_first_out" )? "checked" : "" }}>
                                    <label class="custom-control-label font-16 font-weight-bold"
                                        for="shippingMethodRadio6">First In, First Out</label>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('auto_assign_logic'))
                        <span class="text-danger" role="alert">
                            <strong>{{ $errors->first('auto_assign_logic') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="row mb-2 mt-2 detail-desc @if($allocation->auto_assign_logic != 'one_by_one') hidden-desc @endif " id="one_by_one">
                        <div class="col-md-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="{{asset('assets/images/onebyone.png')}}" alt="img" title="img"
                                            class="rounded" height="90">
                                    </div>
                                    <div class="col-md-10">
                                        <h4 class="header-title">One By One</h4>
                                        <p class="sub-header">Send the task notification to the agent nearest to the
                                            task
                                            location. If the agent doesn't accept the task within request expiry time,
                                            the
                                            task request is send to the next nearest Agent. If no Agent accepts the
                                            task, it
                                            remains unassigned.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2 detail-desc @if($allocation->auto_assign_logic != 'send_to_all') hidden-desc @endif" id="send_to_all">
                        <div class="col-md-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="{{asset('assets/images/onebyone.png')}}" alt="img" title="img"
                                            class="rounded" height="90">
                                    </div>
                                    <div class="col-md-10">
                                        <h4 class="header-title">Send to all</h4>
                                        <p class="sub-header">Request sent to all within a maximum radius. If no one accepts retries.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2  detail-desc @if($allocation->auto_assign_logic != 'batch_wise') hidden-desc @endif" id="batch_wise">
                        <div class="col-md-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="{{asset('assets/images/onebyone.png')}}" alt="img" title="img"
                                            class="rounded" height="90">
                                    </div>
                                    <div class="col-md-10">
                                        <h4 class="header-title">Batch Wise</h4>
                                        <p class="sub-header"> increasing concentric circles based on distance from request - b/w start and increment radius, till max radius. Also limited by batch size and batch count.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2 detail-desc @if($allocation->auto_assign_logic != 'round_robin') hidden-desc @endif" id="round_robin">
                        <div class="col-md-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="{{asset('assets/images/onebyone.png')}}" alt="img" title="img"
                                            class="rounded" height="90">
                                    </div>
                                    <div class="col-md-10">
                                        <h4 class="header-title">Round Robin</h4>
                                        <p class="sub-header">Offers tasks sequentially based on one with least tasks 
forced to nearest  
.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2 detail-desc @if($allocation->auto_assign_logic != 'nearest_available') hidden-desc @endif" id="nearest_available">
                        <div class="col-md-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="{{asset('assets/images/onebyone.png')}}" alt="img" title="img"
                                            class="rounded" height="90">
                                    </div>
                                    <div class="col-md-10">
                                        <h4 class="header-title">Nearest Available</h4>
                                        <p class="sub-header">forced - Assigns tasks sequentially </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2 detail-desc @if($allocation->auto_assign_logic != 'first_in_first_out') hidden-desc @endif" id="first_in_first_out">
                        <div class="col-md-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="{{asset('assets/images/onebyone.png')}}" alt="img" title="img"
                                            class="rounded" height="90">
                                    </div>
                                    <div class="col-md-10">
                                        <h4 class="header-title">First In, First Out</h4>
                                        <p class="sub-header">forced to nearest  </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="task_priority">TASK ALLOCATION PRIORITY</label>
                                <select class="form-control" id="task_priority" name="task_priority">
                                    <option value="default">Default</option>
                                    <option value="other">Other</option>
                                </select>
                                @if($errors->has('task_priority'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('task_priority') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="request_expiry">REQUEST EXPIRES IN SEC</label>
                                <input type="text" name="request_expiry" id="request_expiry" placeholder="30"
                                    class="form-control"
                                    value="{{ old('request_expiry', $allocation->request_expiry ?? '')}}">
                                @if($errors->has('request_expiry'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('request_expiry') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="number_of_retries">NO. OF RETRIES</label>
                                <input type="text" name="number_of_retries" id="number_of_retries" placeholder="0"
                                    class="form-control"
                                    value="{{ old('number_of_retries', $allocation->number_of_retries ?? '')}}">
                                @if($errors->has('number_of_retries'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('number_of_retries') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="start_before_task_time">START ALLOCATION BEFORE TASK TIME (IN
                                    MINUTES)</label>
                                <input type="text" name="start_before_task_time" id="start_before_task_time"
                                    placeholder="0" class="form-control"
                                    value="{{ old('start_before_task_time', $allocation->start_before_task_time ?? '')}}">
                                @if($errors->has('start_before_task_time'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('start_before_task_time') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block" type="submit"> Update </button>
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

    $('.detail-desc').hide();
    $('#'+'{{ $allocation->auto_assign_logic }}').show();

    $(function(){
        $('.custom-logic').change(function(){
            $('.detail-desc').hide();
            $('#'+$(this).val()).show();
        });
    });
</script>
@endsection