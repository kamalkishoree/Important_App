@extends('layouts.vertical', ['title' => 'Agents'])

@section('css')
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- for File Upload -->

    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <style>

        .cursors {
            cursor:move;
            margin-right: 0rem !important;
     }

    
   

    </style>
@endsection
@php
    $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ Session::get('agent_name') }}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                    <div class="alert alert-success">
                                        <span>{!! \Session::get('success') !!}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add {{ Session::get('agent_name') }}</button>
                            <button type="button" class="btn btn-success waves-effect waves-light saveaccounting" data-toggle="modal" data-target="#pay-receive-modal" data-backdrop="static" data-keyboard="false">Pay / Receive</button>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100" id="agents-datatable">
                            <thead>
                                <tr>
                                    <th>Uid</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Type</th>
                                    <th>Team</th>
                                    <th>Vehicle</th>
                                    <th>Cash Collected</th>
                                    <th>Order Earning</th>
                                    <th>Total Received</th>
                                    <th>Total Pay</th>
                                    <th>Final Balance</th>
                                   
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($agents as $agent)
                                <tr> 
                                    <td>
                                        {{ $agent->uid }}
                                    </td>

                                    <td><img alt="{{$agent->id}}" src="{{isset($agent->profile_picture) ? $imgproxyurl.Storage::disk('s3')->url($agent->profile_picture) : Phumbor::url(URL::to('/asset/images/no-image.png')) }}" width="40"></td>
                                    <td class="table-user">
                                        <a href="javascript:void(0);"
                                            class="text-body font-weight-semibold">{{ $agent->name }}</a>
                                    </td>
                                    <td>
                                        {{ $agent->phone_number }}
                                    </td>
                                    <td>
                                        {{ $agent->type }}
                                    </td>
                                    <td>
                                        @if (isset($agent->team->name))
                                            {{ $agent->team->name }}
                                        @else
                                            {{ 'Team Not Alloted' }}
                                        @endif

                                    </td>
                                    <td><img alt=""  style="width: 80px;" src="{{ asset('assets/icons/extra/'. $agent->vehicle_type_id.'.png') }}" ></td>
                                    <!-- <td><span class="badge bg-soft-success text-success">Active</span></td> -->
                                    <td>
                                        {{ $cash = $agent->order->sum('cash_to_be_collected') }}
                                    </td>

                                    <td>
                                        {{ $orders = $agent->order->sum('driver_cost') }}
                                    </td>

                                    <td>
                                        {{ $receive = $agent->agentPayment->sum('cr') }}
                                    </td>

                                    <td>
                                        {{ $pay = $agent->agentPayment->sum('dr') }}
                                    </td>

                                    <td>
                                        {{ ($pay - $receive) - ($cash - $orders) }}
                                    </td>
                                    
                                    
                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div"> <a href1="{{ route('agent.edit', $agent->id) }}" class="action-icon editIcon" agentId="{{$agent->id}}"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{ route('agent.destroy', $agent->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete"></i></button>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- <a href="{{ route('agent.destroy', $agent->id) }}" class="action-icon"> <i class="mdi mdi-delete"></i></a> -->

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $agents->links() }}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>

@include('agent.modals')  
@include('modals.pay-receive')
@endsection

@section('script')

    <script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
    <script src="{{ asset('assets/js/storeAgent.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    {{-- <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>  --}}
    <script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
   
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />>
@include('agent.pagescript')
<script>



     $('#selectAgent').on('change',function (e) {
        
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        $.ajax({
                type: 'get',
                url: "{{ url('/agent/paydetails') }}/"+valueSelected,
                data: '', 
                success: function (data) {
                    console.log(data);
                    var order = round(data.order_cost,2);
                    var cash  = round(data.cash_to_be_collected,2);
                    var final = round(cash - order,2) 
                    $("#order_earning").text(order);
                    $("#cash_collected").text(cash);
                    $("#final_balance").text(final);
                },
        });
        
    });

    function round(value, exp) {
        if (typeof exp === 'undefined' || +exp === 0)
            return Math.round(value);

        value = +value;
        exp = +exp;

        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
            return NaN;

        // Shift
        value = value.toString().split('e');
        value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    }

    $("#submitpayreceive").submit(function(stay){
        
        var formdata = $(this).serialize(); 
            $.ajax({
                type: 'POST',
                url: "{{ route('pay.receive') }}",
                data: formdata, 
                success: function (data) {
                    $("#pay-receive-modal .close").click();
                },
            });
            stay.preventDefault(); 
    });

</script>
@endsection