@extends('layouts.vertical', ['title' => 'Customize'])

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
    <form method="POST" action="{{route('preference', Auth::user()->id)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Theme</h4>
                    <p class="sub-header">
                        Choose between light and dark theme, for the platform.
                    </p>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <p class="text-muted mb-2">SELECT THEME PREFERENCE</p>
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" id="light_theme" value="light" name="theme"
                                    {{ ($preference->theme =="light")? "checked" : "" }}>
                                <label for="light_theme"> Light theme </label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" id="dark_theme" value="dark" name="theme"
                                    {{ ($preference->theme =="dark")? "checked" : "" }}>
                                <label for="dark_theme"> Dark theme </label>
                            </div>
                            @if($errors->has('theme'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('theme') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Nomenclature</h4>
                    <p class="sub-header">
                        Define and update the nomenclature
                    </p>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="agent_type">AGENT TYPE</label>
                                <input type="text" name="agent_name" id="agent_type" placeholder="e.g Driver"
                                    class="form-control" value="{{ old('agent_type', $preference->agent_name ?? '')}}">
                                @if($errors->has('agent_name'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('agent_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="currency">CURRENCY</label>
                                <select class="form-control" id="currency" name="currency_id">
                                    <option value="1" {{ ($preference->currency_id =="1")? "selected" : "" }}>INR
                                    </option>
                                    <option value="2" {{ ($preference->currency_id =="2")? "selected" : "" }}>USD
                                    </option>
                                    <option value="3" {{ ($preference->currency_id =="3")? "selected" : "" }}>AED
                                    </option>
                                </select>
                                @if($errors->has('currency_id'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('currency_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="currency">UNIT</label>
                                <select class="form-control" id="currency" name="distance_unit">
                                    <option value="Kilometers"
                                        {{ ($preference->distance_unit =="Kilometers")? "selected" : "" }}>
                                        Kilometers</option>
                                    <option value="Meters"
                                        {{ ($preference->distance_unit =="Meters")? "selected" : "" }}>
                                        Meters</option>
                                    <option value="Centimeters"
                                        {{ ($preference->distance_unit =="Centimeters")? "selected" : "" }}>
                                        Centimeters</option>
                                </select>
                                @if($errors->has('distance_unit'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('distance_unit') }}</strong>
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
            </div>
        </div>
    </form>

    <form method="POST" action="{{route('preference', Auth::user()->id)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Date & Time</h4>
                    <p class="sub-header">
                        View and update the date & time format.
                    </p>
                    <div class="row mb-2">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="date_format">DATE FORMAT</label>
                                <select class="form-control" id="date_format" name="date_format">
                                    <option value="DD-MM-YYYY"
                                        {{ ($preference->date_format =="DD-MM-YYYY")? "selected" : "" }}>
                                        DD-MM-YYYY</option>
                                    <option value="DD/MM/YYYY"
                                        {{ ($preference->date_format =="DD/MM/YYYY")? "selected" : "" }}>
                                        DD/MM/YYYY</option>
                                    <option value="YYYY-MM-DD"
                                        {{ ($preference->date_format =="YYYY-MM-DD")? "selected" : "" }}>
                                        YYYY-MM-DD</option>
                                </select>
                                @if($errors->has('date_format'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('date_format') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="time_format">TIME FORMAT</label>
                                <select class="form-control" id="time_format" name="time_format">
                                    <option value="12" {{ ($preference->time_format =="12")? "selected" : "" }}>12 hours
                                    </option>
                                    <option value="24" {{ ($preference->time_format =="24")? "selected" : "" }}>24 hours
                                    </option>
                                </select>
                                @if($errors->has('time_format'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('time_format') }}</strong>
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
            </div>
        </div>
    </form>

    <form method="POST" action="{{route('preference', Auth::user()->id)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Tracking URL</h4>
                    <p class="sub-header">
                        Customize the tracking URL.
                    </p>
                    <p class="sub-header m-0">
                        Preview
                    </p>
                    <p class="sub-header ">
                        <code>https://royodispatcher.com/tasktrack</code>/<code
                            style="text-decoration: underline;">Pickup</code>/<code
                            style="text-decoration: underline;">00023</code>
                    </p>
                    <div class="row mb-2">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="task_type">PART 1</label>
                                <select class="form-control" id="task_type" name="task_type">
                                    <option>Task Type</option>
                                    <option value="Pickup" {{ ($preference->task_type =="Pickup")? "selected" : "" }}>
                                        Pickup</option>
                                </select>
                                @if($errors->has('task_type'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('task_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="order_id">PART 2</label>
                                <select class="form-control" id="order_id" name="order_id">
                                    <option>Order Id</option>
                                    <option value="1001" {{ ($preference->order_id =="1001")? "selected" : "" }}>1001
                                    </option>
                                </select>
                                @if($errors->has('order_id'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('order_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h4 class="header-title">Allow Feedback on tracking Url</h4>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" id="feedback1" value="y" name="allow_feedback_tracking_url"
                                    {{ ($preference->allow_feedback_tracking_url =="y")? "checked" : "" }}>
                                <label for="feedback1"> Yes </label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" id="feedback2" value="n" name="allow_feedback_tracking_url"
                                    {{ ($preference->allow_feedback_tracking_url =="n")? "checked" : "" }}>
                                <label for="feedback2"> No </label>
                            </div>
                            @if($errors->has('allow_feedback_tracking_url'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('allow_feedback_tracking_url') }}</strong>
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

</div> <!-- container -->
@endsection

@section('script')

@endsection