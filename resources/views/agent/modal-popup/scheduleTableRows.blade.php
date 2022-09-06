@section('popup-id','scheduleTablePopup')
<style>
    #scheduleTablePopup .dt-buttons.btn-group.flex-wrap {right: inherit;}
</style>
@section('popup-header')
Booking Schedule For:<p class="sku-name pl-1"></p>
@endsection
@section('popup-content')
<table id="scheduleTable" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Start Date/Time</th>
            <th>End Date/Time</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Start Date/Time</th>
            <th>End Date/Time</th>
        </tr>
    </tfoot>
</table>

<br>
<h4>Manual Date/Time</h4>
<table id="blockTimeTable" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Start Date/Time</th>
            <th>End Date/Time</th>
            <th>Memo</th>
            <th>Action</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Start Date/Time</th>
            <th>End Date/Time</th>
            <th>Memo</th>
            <th>Action</th>
            {{-- <th>Status</th> --}}
        </tr>
    </tfoot>
</table>

@endsection

@section('popup-js')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" ></script> --}}
<script src="{{ asset('assets/js/agent/agentSlot.js')}}"></script>
@endsection