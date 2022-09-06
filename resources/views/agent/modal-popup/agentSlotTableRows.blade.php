<link href="{{asset('assets/js/fullcalendar/calendar_main-5.9.css')}}" rel="stylesheet" type="text/css" />
@section('popup-id','agentTablePopup')
<style>
    #scheduleTablePopup .dt-buttons.btn-group.flex-wrap {right: inherit;}
</style>
@section('popup-header')
Agent schedule slots:<p class="sku-name pl-1"></p>
@endsection
@section('popup-content')
<div class="card-box">
    <div class="row">
        <h4 class="mb-4 "> {{ __('Weekly Slot') }}</h4>
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
@endsection

@section('popup-js')
<script>
    var Agent_calender_url =`{{route('agent.calender.data',':id')}}`;
    //Agent_calender_url.replace(":id", category);
    
</script>
    <script src="{{asset('assets/js/fullcalendar/calendar_main-5.9.js')}}"></script>
    <script src="{{ asset('assets/js/agent/agentSlot.js')}}"></script>
@endsection