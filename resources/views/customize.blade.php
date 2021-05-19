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

    <div class="row">
        <div class="col-md-4">
            <form method="POST" action="{{route('preference', Auth::user()->code)}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
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
        </div>
        <div class="col-md-4">
            <form method="POST" action="{{route('preference', Auth::user()->code)}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <h4 class="header-title">Address</h4>
                            <p class="sub-header">
                                Choose between all address and my address, for the platform.
                            </p>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="radio radio-info form-check-inline">
                                        <input type="radio" id="all_contact" value="1" name="allow_all_location" {{ (isset($preference) && $preference->allow_all_location ==1)? "checked" : "" }}>
                                        <label for="all_contact"> All Addresses </label>
                                    </div>
                                    <div class="radio form-check-inline">
                                        <input type="radio" id="my_contact" value="0" name="allow_all_location" {{ (isset($preference) &&  $preference->allow_all_location ==0)? "checked" : "" }}>
                                        <label for="my_contact"> My Addresses </label>
                                    </div>
                                    @if($errors->has('allow_all_location'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('allow_all_location') }}</strong>
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
        </div>
        <div class="col-md-4">
            <div class="card-box">
                <h4 class="header-title">CMS</h4>
                <p class="sub-header">
                    View and update the date & time format.
                </p>
                <div class="row">
                    <div class="login-forms">
                        <ul class="list-inline mb-0">
                            <li class="d-inline-block ml-2">
                               
                    
                            <label for="acknowledge1"><a href="#" class="btn btn-blue btn-block" type="button" data-toggle="modal" data-target="#create-tandc-modal">Terms and Conditions</a></label>
                            </li>
                            <li class="d-inline-block ml-2">
                                
                            <label for="acknowledge2"><a href="#" class="btn btn-blue btn-block" type="button" data-toggle="modal" data-target="#create-pandp-modal">Privacy Policy</a></label>
                            </li>
                          </ul>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <form method="POST" action="{{route('preference', Auth::user()->code)}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
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
        
                                <div class="col-md-12">
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
        </div>
        <div class="col-md-6">
            <form method="POST" action="{{route('preference',Auth::user()->code)}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <h4 class="header-title">Date & Time</h4>
                            <p class="sub-header">
                                View and update the date & time format.
                            </p>
                            <div class="row mb-lg-5 mb-2">
        
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="date_format">DATE FORMAT</label>
                                        <select class="form-control" id="date_format" name="date_format">
                                            <option value="m/d/Y" {{ ($preference && $preference->date_format =="m/d/Y")? "selected" : "" }}>
                                                MM/DD/YYYY</option>
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
        </div>
    </div>

        <form method="POST" action="{{route('task.proof')}}">
        @csrf
        <div class="row">
            <div class="col-xl-12">
                <div class="card-box">
                    <h4 class="header-title mb-3">Task Completion Proofs</h4>
                    
                    <div>
                        {{-- @php 
                            echo "<pre>";
                            print_r($task_list); @endphp --}}
                        @foreach ($task_proofs as $key => $taskproof)
                        @php $counter = 1; @endphp
                        <h5 class="header-title mb-3">{{$task_list[$key]->name}}</h5>
                         
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
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'1'}}" name="image_{{$key+1}}" {{isset($taskproof->image) && $taskproof->image == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'1'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'2'}}" name="image_requried_{{$key+1}}" {{isset($taskproof->image_requried) && $taskproof->image_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'2'}}"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Signature</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'3'}}" name="signature_{{$key+1}}" {{isset($taskproof->signature) && $taskproof->signature == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'3'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'4'}}" name="signature_requried_{{$key+1}}" {{isset($taskproof->signature_requried) && $taskproof->signature_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'4'}}"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Notes</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'5'}}" name="note_{{$key+1}}" {{isset($taskproof->note) && $taskproof->note == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'5'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'6'}}" name="note_requried_{{$key+1}}" {{isset($taskproof->note_requried) && $taskproof->note_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'6'}}"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>


                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Barcode</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'7'}}" name="barcode_{{$key+1}}" {{isset($taskproof->barcode) && $taskproof->barcode == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'7'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'8'}}" name="barcode_requried_{{$key+1}}" {{isset($taskproof->barcode_requried) && $taskproof->barcode_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'8'}}"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>


                                </tbody>
                            </table>
                         </div>
                         {{-- <h4 class="header-title mb-3">{{$key == 0 ? 'Drop-Off': $key == 1 ? 'Appointment':''}}</h4> --}}
                         @php $counter++; @endphp
                        @endforeach
                        
                        {{-- <h4 class="header-title mb-3">Drop-Off</h4>
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
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_7" name="image_2" {{isset($taskproof->image) && $taskproof->image == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_7"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_8" name="image_requried_2" {{isset($taskproof->image_requried) && $taskproof->image_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_8"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Signature</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_9" name="signature_2" {{isset($taskproof->signature) && $taskproof->signature == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_9"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_10" name="signature_requried_2" {{isset($taskproof->signature_requried) && $taskproof->signature_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_10"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Notes</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_11" name="note_2" {{isset($taskproof->note) && $taskproof->note == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_11"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_12" name="note_requried_2" {{isset($taskproof->note_requried) && $taskproof->note_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_12"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>


                                </tbody>
                            </table>
                        </div> --}}

                        {{-- <h4 class="header-title mb-3">Appointment</h4>
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
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_13" name="image_3" {{isset($taskproof->image) && $taskproof->image == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_13"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_14" name="image_requried_3" {{isset($taskproof->image_requried) && $taskproof->image_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_14"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Signature</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_15" name="signature_3" {{isset($taskproof->signature) && $taskproof->signature == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_15"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_16" name="signature_requried_3" {{isset($taskproof->signature_requried) && $taskproof->signature_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_16"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Notes</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_17" name="note_3" {{isset($taskproof->note) && $taskproof->note == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_17"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_18" name="note_requried_3" {{isset($taskproof->note_requried) && $taskproof->note_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_18"></label>
                                            </div>
                                        </td>

                                        
                                    </tr>


                                </tbody>
                            </table>
                        </div> --}}

                        
            
                        
                      
                        
                        
                        
                        
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


        $(document).on('click', '[name="myRadios"]', function () {

            if (lastSelected != $(this).val() && typeof lastSelected != "undefined") {
                alert("radio box with value " + $('[name="myRadios"][value="' + lastSelected + '"]').val() + " was deselected");
            }
            lastSelected = $(this).val();

        });

    });

</script>
@endsection