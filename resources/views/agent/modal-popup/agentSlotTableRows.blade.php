@section('customcss')
<link href="{{asset('assets/js/fullcalendar/calendar_main-5.9.css')}}"
	rel="stylesheet" type="text/css" />
<link
	href="{{ asset('assets/libs/datetimepicker/daterangepicker.css') }}"
	rel="stylesheet" type="text/css" />
@endsection @php $getAdditionalPreference =
getAdditionalPreference(['pickup_type',
'drop_type','is_attendence','idle_time']); @endphp
@section('popup-id','agentTablePopup')
<style>
#scheduleTablePopup .dt-buttons.btn-group.flex-wrap {
	right: inherit;
}

.alWeeklyHourPopup .nav-link {
	background-color: transparent;
	color: #777;
}
#agentTablePopup{
    z-index:1045;
}
#agentTablePopup .modal-body {
	padding-top: 0px;
}

.p-align {
	text-align: left
}

#duration {
	display: none
}
/* .fc-event-time{display:none}*/
</style>
{{-- @section('popup-header') {{ __('Agent Weekly hours') }}:
<p class="sku-name pl-1"></p>
@endsection --}} @section('popup-content')
<div class="card-box pt-0">
	<div class="row">
		<div class="col-12 d-flex justify-content-between">
			<div class="text-left">
				<h4 class="mb-4 ">{{ __('Scheduled Hours') }}</h4>
			</div>
			<div class="text-right">
				<h4 class="mb-4 " id="duration"></h4>
			</div>
		</div>
		<div class="col-12 alWeeklyHourPopup">

			<ul class="nav nav-tabs" id="myTab" role="tablist">
				@if($is_driver_slot == 1)
				<li class="nav-item" role="presentation">
					<button class="get_event  nav-link active" id="workinghours-tab"
						data-toggle="tab" data-eventType="working_hours"
						data-target="#workinghours" type="button" role="tab"
						aria-controls="workinghours" aria-selected="true">Working hours</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="get_event nav-link" id="booking-tab"
						data-toggle="tab" data-eventType="new_booking"
						data-target="#booking" type="button" role="tab"
						aria-controls="booking" aria-selected="false">Bookings</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="get_event nav-link" id="blocked-tab"
						data-toggle="tab" data-eventType="blocked" data-target="#blocked"
						type="button" role="tab" aria-controls="blocked"
						aria-selected="false">Blocked</button>
				</li> @endif @if($getAdditionalPreference['is_attendence'] == 1)
				<li class="nav-item" role="presentation">
					<button
						class="get_attendence nav-link @if($is_driver_slot == 0) active @endif"
						id="attendence-tab" data-toggle="tab" data-eventType="attendence"
						data-target="#attendence" type="button" role="tab"
						aria-controls="attendence" aria-selected="false">Attendence</button>
				</li> @endif
			</ul>
		</div>

		<div class="col-md-12">
			<div class="row mb-2">
				<div class="col-md-12 col-lg-3">
					<div id='calendar_slot_alldays'>

						<table class="table table-centered table-nowrap table-striped"
							id="calendar_slot_alldays_table">
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
				<div class="col-md-12 col-lg-9">
					<div id='calendar'></div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection @section('popup-js')
<script>
    var Agent_calender_url =`{{route('agent.calender.data',':id')}}`;
        var AgentAttendence_calender_url =`{{route('agent.attendence.calender.data',':id')}}`;
    
    //Agent_calender_url.replace(":id", category);
    
</script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script src="{{asset('assets/libs/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/js/fullcalendar/calendar_main-5.9.js')}}"></script>
<script
	src="{{asset('assets/libs/datetimepicker/daterangepicker.min.js')}}"></script>
<script src="{{ asset('assets/js/agent/agentAttendence.js')}}"></script>
@if($is_driver_slot == 1)
<script src="{{ asset('assets/js/agent/agentSlot.js')}}"></script>
@endif @endsection
