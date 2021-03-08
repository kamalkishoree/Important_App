@extends('layouts.vertical', ['title' => 'Customize'])

@section('css')
@endsection
@section('content')
@include('modals.tandc')
@include('modals.privacyandpolicy')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Customize</h4>
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
    <form method="POST" action="{{route('preference', Auth::user()->code)}}">
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
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" id="light_theme" value="light" name="theme" {{ (isset($preference) && $preference->theme =="light")? "checked" : "" }}>
                                <label for="light_theme"> Light theme </label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" id="dark_theme" value="dark" name="theme" {{ (isset($preference) &&  $preference->theme =="dark")? "checked" : "" }}>
                                <label for="dark_theme"> Dark theme </label>
                            </div>
                            @if($errors->has('theme'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('theme') }}</strong>
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

    <form method="POST" action="{{route('preference', Auth::user()->code)}}">
        @csrf
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
                                <label for="agent_type">AGENT NAME</label>
                                <input type="text" name="agent_name" id="agent_type" placeholder="e.g Driver" class="form-control" value="{{ old('agent_type', $preference->agent_name ?? '')}}">
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
                                    @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ ($preference && $preference->currency_id == $currency->id)? "selected" : "" }}>{{ $currency->iso_code }} - {{ $currency->symbol }}
                                    </option>
                                    @endforeach
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
                            <label>Unit</label>
                            <div class="col-sm-12">
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="metric" value="metric" name="distance_unit" {{ ($preference && $preference->distance_unit =="metric")? "checked" : "" }}>
                                    <label for="metric"> Metric</label>
                                </div>
                                <div class="radio form-check-inline">
                                    <input type="radio" id="imperial" value="imperial" name="distance_unit" {{ ($preference && $preference->distance_unit =="imperial")? "checked" : "" }}>
                                    <label for="imperial"> Imperial</label>
                                </div>
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
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form method="POST" action="{{route('preference',Auth::user()->code)}}">
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
                                    <option value="d-m-Y" {{ ($preference && $preference->date_format =="d-m-Y")? "selected" : "" }}>
                                        DD-MM-YYYY</option>
                                    <option value="d/m/Y" {{ ($preference && $preference->date_format =="d/m/Y")? "selected" : "" }}>
                                        DD/MM/YYYY</option>
                                    <option value="Y-m-d" {{ ($preference && $preference->date_format =="Y-m-d")? "selected" : "" }}>
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
                                    <option value="12" {{ ($preference && $preference->time_format =="12")? "selected" : "" }}>12 hours
                                    </option>
                                    <option value="24" {{ ($preference && $preference->time_format =="24")? "selected" : "" }}>24 hours
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
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Cms</h4>
                    <p class="sub-header">
                        View and update the date & time format.
                    </p>
                    <div class="row">
                        <div class="login-forms">
                            <ul class="list-inline">
                                <li class="d-inline-block ml-2">
                                   
                        
                                <label for="acknowledge1"><a href="#" class="btn btn-blue btn-block" type="button" style="color: #ffffff;" data-toggle="modal" data-target="#create-tandc-modal">Terms and Conditions</a></label>
                                </li>
                                <li class="d-inline-block ml-2">
                                    
                                <label for="acknowledge2"><a href="#" class="btn btn-blue btn-block" type="button" style="color: #ffffff;" data-toggle="modal" data-target="#create-pandp-modal">Privacy Policy</a></label>
                                </li>
                              </ul>
                            </div>
                    </div>

                </div>
            </div>
        </div>

        <form method="POST" action="{{route('task.proof')}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title mb-3">Task Completion Proofs</h4>
                    
                    <h5 class="header-title mb-3">Pickup</h5>
                    <div>

                        <div class="table-responsive table_spacing">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Enable</th>
                                        <th>Required</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    </td>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Image</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_1" name="image[]" {{isset($taskproof->image) && $taskproof->image == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_2" name="image_requried[]" {{isset($taskproof->image_requried) && $taskproof->image_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_2"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Signature</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_3" name="signature[]" {{isset($taskproof->signature) && $taskproof->signature == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_3"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_4" name="signature_requried[]" {{isset($taskproof->signature_requried) && $taskproof->signature_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_4"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Notes</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_5" name="note[]" {{isset($taskproof->note) && $taskproof->note == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_5"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_6" name="note_requried[]" {{isset($taskproof->note_requried) && $taskproof->note_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_6"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                        <h4 class="header-title mb-3">Drop-Off</h4>
                        <div class="table-responsive table_spacing">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Enable</th>
                                        <th>Required</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    </td>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Image</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_1" name="image[]" {{isset($taskproof->image) && $taskproof->image == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_2" name="image_requried[]" {{isset($taskproof->image_requried) && $taskproof->image_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_2"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Signature</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_3" name="signature[]" {{isset($taskproof->signature) && $taskproof->signature == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_3"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_4" name="signature_requried[]" {{isset($taskproof->signature_requried) && $taskproof->signature_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_4"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Notes</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_5" name="note[]" {{isset($taskproof->note) && $taskproof->note == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_5"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_6" name="note_requried[]" {{isset($taskproof->note_requried) && $taskproof->note_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_6"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                        <h4 class="header-title mb-3">Appointment</h4>
                        <div class="table-responsive table_spacing">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Enable</th>
                                        <th>Required</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    </td>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Image</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_1" name="image[]" {{isset($taskproof->image) && $taskproof->image == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_2" name="image_requried[]" {{isset($taskproof->image_requried) && $taskproof->image_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_2"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Signature</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_3" name="signature[]" {{isset($taskproof->signature) && $taskproof->signature == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_3"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_4" name="signature_requried[]" {{isset($taskproof->signature_requried) && $taskproof->signature_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_4"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Notes</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_5" name="note[]" {{isset($taskproof->note) && $taskproof->note == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_5"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_6" name="note_requried[]" {{isset($taskproof->note_requried) && $taskproof->note_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_6"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>


                                </tbody>
                            </table>
                        </div>

                        
            
                        
                      
                        
                        
                        
                        
                    </div>

                    <div class="row mb-2 mt-2">
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
    

    

</div> <!-- container -->
@endsection

@section('script')
<script>

    $(document).ready(function() {



        var CSRF_TOKEN = $("input[name=_token]").val();
        
      

        $( '#tandc_form' ).on( 'submit', function(e) {
            e.preventDefault();

            var content = $(this).find('textarea[name=content]').val();

           
            $.ajax({
            type: "POST",
            url: "{{ route('cms.save',1) }}",
            data: { _token: CSRF_TOKEN,content:content}, 
            success: function( msg ) {
                $("#create-tandc-modal .close").click();
            }
           });

        });

        $( '#pandp_form' ).on( 'submit', function(e) {
            e.preventDefault();

            var content = $(this).find('textarea[name=content]').val();

           
            $.ajax({
            type: "POST",
            url: "{{ route('cms.save',2) }}",
            data: { _token: CSRF_TOKEN,content:content}, 
            success: function( msg ) {
                $("#create-pandp-modal .close").click();
            }
           });

        });

    });

</script>
@endsection