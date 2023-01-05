
@extends('layouts.vertical', ['title' => 'General Slots'])
@section('customcss')

    <link href="{{asset('assets/js/fullcalendar/calendar_main-5.9.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datetimepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('popup-id','agentTablePopup')

<style>
    #scheduleTablePopup .dt-buttons.btn-group.flex-wrap {right: inherit;}
    .alWeeklyHourPopup .nav-link{background-color: transparent; color: #777;}
    #agentTablePopup .modal-body {padding-top: 0px;}
</style>
{{-- @section('popup-header')
{{ __('Agent Weekly hours') }}:<p class="sku-name pl-1"></p>
@endsection --}}

<div class="card-box pt-0">
    <div class="row">
        <h4 class="mb-4 "> {{ __('Scheduled Hours') }}</h4>
        <div class="col-12 alWeeklyHourPopup">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="get_event  nav-link active" id="workinghours-tab" data-toggle="tab" data-eventType="working_hours" data-target="#workinghours" type="button" role="tab" aria-controls="workinghours" aria-selected="true">Working hours</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="get_event nav-link" id="booking-tab" data-toggle="tab" data-eventType="new_booking" data-target="#booking" type="button" role="tab" aria-controls="booking" aria-selected="false">Bookings</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="get_event nav-link" id="blocked-tab" data-toggle="tab" data-eventType="blocked" data-target="#blocked" type="button" role="tab" aria-controls="blocked" aria-selected="false">Blocked</button>
                </li>
            </ul>
            {{-- <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">1 11111...</div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">2 22222...</div>
            </div> --}}
        </div>

        <div class="col-md-12">
            <div class="row mb-2">
                <div class="col-md-12 col-lg-4">
                    <div id='calendar_slot_alldays'>

                        <table class="table table-centered table-nowrap table-striped" id="calendar_slot_alldays_table">
                            <thead>
                                <tr>
                                    <th colspan="2">This week</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <div id='calendar'>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('popup-js')
<script>
    var AddSlotHtml = `<form class="needs-validation" name="slot-form" id="slot-event" action="" method="post">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ __("Start/End Date") }}</label>

                                        <input id="blocktime" class="form-control" autofocus>
                                    </div>
                                </div>
                                
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __("Start Time(24 hours format)") }}</label>
                                            <input class="form-control" placeholder="Start Time" type="time" name="start_time" id="start_time" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __("End Time(24 hours format)") }}</label>
                                            <input class="form-control" placeholder="End Time" type="time" name="end_time" id="end_time" required />
                                        </div>
                                    </div>
                                
                            
                                </div>
                                <div class="row memo">
                                    <div class="col-md-6 slot_type">
                                        <label class="d-block">Slot Type</label>
                                            <select id="booking_type">
                                                <option selected value="working_hours">Working hours</option>
                                                <option value="blocked">Block</option>
                                            </select>
                                    </div>
                                    <div class="col-md-6 slotForDiv">
                                        {!! Form::label('title', 'Recurring',['class' => 'control-label']) !!}
                                    <div class="form-group">
                                      
                                        <ul class="list-inline">
                                            <li class="d-block pl-1 ml-3 mb-1 custom-radio-design">
                                                <input type="checkbox" class="custom-control-input check recurring" id="recurring" name="recurring">
                                                <label class="custom-control-label"  for="recurring">Yes</label>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                                </div>
                                <div class="row forDate" style="display: none;">
                                <input type="hidden" class="custom-control-input methods" value="services">
                                </div>
                                <div class="row memo">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="d-block pt-2">Memo</label>
                                            <textarea placeholder="" id="memo" class="form-control memo"></textarea>
                                         </div>
                                    </div>
                                </div>
                             
                            <div class="row mb-2 weekDays" style="display:none">
                                <div class="col-md-12">
                                    <div class="">
                                    {!! Form::label('title', __('Select days of week'),['class' => 'control-label']) !!}
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_1" value="1">
                                        <label for="day_1"> {{ __("Sunday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_2" value="2">
                                        <label for="day_2"> {{ __('Monday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_3" value="3">
                                        <label for="day_3"> {{ __("Tuesday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_4" value="4">
                                        <label for="day_4"> {{ __("Wednesday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_5" value="5">
                                        <label for="day_5"> {{ __('Thursday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_6" value="6">
                                        <label for="day_6"> {{ __('Friday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_7" value="7">
                                        <label for="day_7"> {{ __('Saturday') }} </label>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row forDate" style="display: none;">
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <label class="control-label">{{ __("Slot Date") }}</label>
                                        <input class="form-control date-datepicker" placeholder={{ __("Select Date") }} type="text" name="slot_date" id="slot_date" required />
                                    </div>
                                </div>
                        
                            </div>
                        
                        </form>`;

                    var EditSlotHtml = `<form class="needs-validation" name="slot-form" id="update-event" action="" method="post">
                            @csrf
                            <input type="hidden" name="slot_day_id" id="slot_day_id" value="" >
                            <input type="hidden" name="slot_id" id="edit_slot_id" value="" >
                            <input type="hidden" name="edit_booking_type_old" id="edit_booking_type_old" value="" >
                            <input type="hidden" name="old_slot_type" id="edit_slot_type_old" value="" >
                            <input type="hidden" name="slot_date" id="edit_slot_date_D" value="" >
                            <input type="hidden" name="blocktime" id="edit_blocktime" value="" >
                            <input type="hidden" name="blocktime" id="edit_recurring" value="" >
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ __("Start/End Date") }}</label>

                                        <input id="blocktime" class="form-control"  autofocus>
                                    </div>
                                </div>
                                
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __("Start Time(24 hours format)") }}</label>
                                            <input class="form-control" placeholder="Start Time" type="time" name="start_time" id="edit_start_time" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __("End Time(24 hours format)") }}</label>
                                            <input class="form-control" placeholder="End Time" type="time" name="end_time" id="edit_end_time" required />
                                        </div>
                                    </div>
                                
                            
                                </div>
                                <div class="row memo view_booking">
                                    <div class="col-md-6 slot_type">
                                        <label class="d-block">Slot Type</label>
                                            <select id="edit_booking_type">
                                                <option selected value="working_hours">Working hours</option>
                                                <option value="blocked">Block</option>
                                            </select>
                                    </div>
                                    <div class="col-md-6 slotForDiv">
                                        {!! Form::label('title', 'Recurring',['class' => 'control-label']) !!}
                                    <div class="form-group">
                                      
                                        <ul class="list-inline">
                                            <li class="d-block pl-1 ml-3 mb-1 custom-radio-design">
                                                <input type="checkbox" class="custom-control-input check edit_recurring recurring" id="recurring" name="recurring">
                                                <label class="custom-control-label"  for="recurring">Yes</label>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                                </div>
                                <div class="row forDate" style="display: none;">
                                <input type="hidden" class="custom-control-input" id="method"  value="services">
                                </div>
                                <div class="row memo">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="d-block pt-2">Memo</label>
                                            <textarea placeholder="" id="edit_memo" class="form-control memo"></textarea>
                                         </div>
                                    </div>
                                </div>
                             
                            <div class="row mb-2 weekDays view_booking" style="display:none">
                                <div class="col-md-12">
                                    <div class="">
                                    {!! Form::label('title', __('Select days of week'),['class' => 'control-label']) !!}
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_1" value="1">
                                        <label for="day_1"> {{ __("Sunday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_2" value="2">
                                        <label for="day_2"> {{ __('Monday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_3" value="3">
                                        <label for="day_3"> {{ __("Tuesday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_4" value="4">
                                        <label for="day_4"> {{ __("Wednesday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_5" value="5">
                                        <label for="day_5"> {{ __('Thursday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_6" value="6">
                                        <label for="day_6"> {{ __('Friday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_7" value="7">
                                        <label for="day_7"> {{ __('Saturday') }} </label>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row forDate" style="display: none;">
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <label class="control-label">{{ __("Slot Date") }}</label>
                                        <input class="form-control date-datepicker" placeholder={{ __("Select Date") }} type="text" name="slot_date" id="edit_slot_date" required />
                                    </div>
                                </div>
                        
                            </div>
                            <input  name="edit_type" type="hidden" id="edit_type" value="">
                            <input  name="edit_day" type="hidden" id="edit_day" value="">
                            <input name="edit_type_id" type="hidden" id="edit_type_id" value="">
                            <div class="row mt-2 view_booking">
                                <div class="col-12 mb-2">
                                    <button type="button" class="btn btn-danger w-100" id="deleteSlotBtn">{{ __("Delete Slot") }}</button>
                                </div>
                            </div>
                            <div class="row mt-2 view_orderDetails" style="display: none;">
                                <div class="col-12 mb-2">
                                    <a  class="btn btn-info w-100" target="_blank" id="viewOrder">{{ __("View Order") }}</a>
                                </div>
                            </div>
                        
                        </form>`;
</script>
<script>
    var Agent_calender_url =`{{route('agent.calender.data',':id')}}`;
    //Agent_calender_url.replace(":id", category);
    
</script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script src="{{asset('assets/libs/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/js/fullcalendar/calendar_main-5.9.js')}}"></script>
<script src="{{asset('assets/libs/datetimepicker/daterangepicker.min.js')}}" ></script>
<script src="{{ asset('assets/js/agent/agentSlot.js')}}"></script>
    
@endsection