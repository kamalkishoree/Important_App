@extends('layouts.vertical', ['title' => 'Task'])

@section('css')
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />


<!-- for File Upload -->

<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/css/intlTelInput.css'>
<style>
    // workaround
    .intl-tel-input {
      display: table-cell;
    }
    
    .inner-div {
            width: 50%;
            float: left;
        }
    .intl-tel-input .selected-flag {
      z-index: 4;
    }
    .intl-tel-input .country-list {
      z-index: 5;
    }
    .input-group .intl-tel-input .form-control {
      border-top-left-radius: 4px;
      border-top-right-radius: 0;
      border-bottom-left-radius: 4px;
      border-bottom-right-radius: 0;
    }
    #radio1, #radio2, #radio3, #radio4 {  
        -ms-transform: scale(1.2); /* IE 9 */
        -webkit-transform: scale(1.2); /* Chrome, Safari, Opera */
        transform: scale(1.2); }
    .showspan{
        font-weight: normal;
    }
    .showtasks{
        border: none;
        outline:none;
    }
    
    
    .assigned-block {
      background-color: #EAECFD;
    }
    .assigned-block h6 {
      color: #272727;
      font-size: 13px;
      letter-spacing: 0;
      line-height: 15px;
      margin-bottom: 0px;
      margin-top: 4px;
    } 
    .assigned-block span {
      color: #797979;
      font-size: 13px;
      letter-spacing: 0;
    }
    .assigned-block h5 {
       color: #272727;
      font-size: 13px;
      font-weight: 500;
      letter-spacing: 0;
    }
    .assigned-block a {
      color: #797979;
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 0;
      border-bottom: 1px dashed #979797;
    }
    .assigned-btn {
      border-radius: 10px;
      background-color: rgb(44 129 255 / .21);
      color: #5664EA;
      padding: 4px 10px;
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 0;
      line-height: 13px;
      border: none;
    }
    .wd-10{
        width: 6%;
        display: inline-block;
        vertical-align: top;
        padding-top: 4px;
    }
    .wd-90{
        width: 89%;
        display: inline-block;
    }
    .content-page {
    margin-left: 240px;

    padding: 0 15px 65px 15px;
    min-height: 80vh;
    margin-top: 70px;
    height: 98vh;
    overflow-y: scroll !important;
    }
    .dispaly-cards{
        background-color: rgb(251 247 247) !important;
         text-align: center;
         padding: 5px !important;
         
    }
    .setcolor{
        color: blue !important;
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
                <h4 class="page-title">{{ auth()->user()->getPreference->agent_name ?? 'Tasks' }}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div> 
                        @csrf
                        <div class="col-sm-6">
                        <form name="getTask" id="getTask" method="get" action="{{route('tasks.index')}}">
                            <div class="login-form">
                              <ul class="list-inline">
                                <li class="d-inline-block mr-2">
                                    <input type="radio" id="teacher" name="status" onclick="handleClick(this);" value="unassigned" {{$status == "unassigned"?"checked":''}}>
                                    <label for="teacher">Pending<span class="showspan">{{' ('.$panding_count.')'}}</span></label>
                                  </li>
                                <li class="d-inline-block mr-2">
                                  <input type="radio" id="student" onclick="handleClick(this);" name="status" value="assigned" {{$status == "assigned"?"checked":''}}>
                                  <label for="student">Active<span class="showspan">{{' ('.$active_count.')'}}</span></label>
                                </li>
                                
                  
                                <li class="d-inline-block mr-2">
                                  <input type="radio" id="parent" name="status"  onclick="handleClick(this);" value="completed" {{$status == "completed"?"checked":''}}>
                                  <label for="parent">History<span class="showspan">{{' ('.$history_count.')'}}</span></label>
                                </li>
                              </ul>
                            </div>
                        </form>
                        </div>
                        <div class="col-sm-2"></div>
                        {{-- <div class="col-sm-4 text-right">
                            <!--<button type="button" class="btn btn-blue waves-effect waves-light showTaskPop" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Task</button> -->
                         <a href="{{ route('tasks.create') }}" class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i> Add Task</a>
                        </div> --}}

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100"  id="agents-datatable">
                            <thead>
                                <tr>
                                    {{-- <th>Id</th> --}}
                                    <th>Customer</th>  
                                    <th>Phone.No</th>
                                    <th>Driver</th>
                                    <th>Create Time</th>
                                    <th>Due Time</th>
                                    <th>Tasks</th>
                                    <th>Pricing</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr>
                                    
                                    {{-- <td>
                                        {{$task->id}}
                                    </td> --}}
                                    <td>
                                        {{$task->customer->name}}
                                    </td>  
                                    <td>
                                        {{$task->customer->phone_number}}
                                    </td>
                                    <td>
                                        {{ empty($task->agent) ? 'Unassigned' : $task->agent->name }}
                                    </td>
                                    <td>
                                        {{$task->order_time}}
                                    </td>
                                    <td>
                                        {{$task->created_at}}
                                    </td>
                                    <td>
                                        <button class="showtasks" value="{{$task->id}}"><i class="fe-eye"></i></button>
                                    </td>
                                    <td>
                                        <button class="showaccounting btn btn-primary-outline action-icon setcolor" value="{{$task->id}}">{{$task->order_cost}}</button>
                                    </td>

                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div"> <div class="set-size"> <a href1="#" href="{{route('tasks.edit', $task->id)}}"  class="action-icon editIconBtn"> <i class="mdi mdi-square-edit-outline"></i></a></div></div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{route('tasks.destroy', $task->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete"></i></button>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>


</div>

@include('modals.task-list')
@include('modals.task-accounting')
@endsection

@section('script')


<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>


<script src="{{asset('assets/js/storeAgent.js')}}"></script>

<!-- for File Upload -->
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>



<script>



$(document).ready( function () {
    $('#agents-datatable').DataTable();
});

    function handleClick(myRadio) {
        $('#getTask').submit();
    }

    $(document).on('click', '.showtasks', function () {
      var CSRF_TOKEN = $("input[name=_token]").val();
      var tour_id = $(this).val();
      var basic = window.location.origin;
      var url = basic+"/tasks/list/"+tour_id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
            _token: CSRF_TOKEN,
            status:status
            },
            success: function(data) {
                console.log(data.task);
                // console.log(data[0].task);
                 //abc = $('#removedata').html('');
                //console.log(abc);
                // $('#removedata').hide();
                $('.repet').remove();
                var taskname = '';
                $.each(data.task, function(index, elem){
                  
                      
                    switch (elem.task_type_id) {
                        case 1:
                              taskname = 'Pickup task';
                            break;
                        case 2:
                              taskname = 'Drop Off task';
                            break;
                        case 3:
                              taskname = 'Appointment';
                            break;
                    }
                    var date = new Date(elem.order_time);
                    var options = { hour12: true };
                    $(document).find('.allin').before('<div class="repet"><div class="task-card p-3"><div class="p-2 assigned-block"><h5>'+taskname+'</h5><div class="wd-10"><img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}"></div><div class="wd-90"><h6>'+elem.location.address+'</h6><span>'+elem.location.short_name+'</span><h5 class="mb-1"><span></span></h5><div class="row"><div class="col-md-6"></div><div class="col-md-6 text-right"><button class="assigned-btn">'+data.status+'</button></div></div></div></div></div></div>');
                  
                         
                });

                $('#task-list-modal').modal('show');
                 
            }
                            
        });
    });

    $(document).on('click', '.showaccounting', function () {
        // $('#task-accounting-modal').modal('show');
        //   return;
      var CSRF_TOKEN = $("input[name=_token]").val();
      var tour_id = $(this).val();
      var basic = window.location.origin;
     
      var url = basic+"/tasks/list/"+tour_id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
            _token: CSRF_TOKEN,
            },
            success: function(data) {
               
                $("#base_distance").text(round(data.base_distance));
                $("#actual_distance").text(data.actual_distance);
                $("#billing_distance").text(round(data.actual_distance - data.base_distance,2));
                var sendDistance = (data.actual_distance - data.base_distance) * data.distance_fee;
                $("#distance_cost").text(round(sendDistance,2));

                $("#base_duration").text(data.base_duration);
                $("#actual_duration").text(data.actual_time);
                $("#billing_duration").text(data.actual_time - data.base_duration);
                var sendDuration = (data.actual_time - data.base_duration) * data.duration_price;
                $("#duration_cost").text(sendDuration);

                $("#base_price").text(data.base_price);
                $("#duration_price").text(data.duration_price + ' (Per min)');
                $("#distance_fee").text(data.distance_fee +' ('+data.distance_type + ')');
                $("#driver_type").text(data.driver_type);

                $("#order_cost").text(data.order_cost);
                $("#driver_cost").text(data.driver_cost != 0.00 ? data.driver_cost :'Not assigned yet'); 

                $("#base_waiting").val(data.base_waiting);
                $("#distance_fee").val(data.distance_fee);
                $("#cancel_fee").val(data.cancel_fee);
                $("#agent_commission_percentage").text(data.agent_commission_percentage);
                $("#agent_commission_fixed").text(data.agent_commission_fixed);
                $("#freelancer_commission_percentage").text(data.freelancer_commission_percentage);
                $("#freelancer_commission_fixed").text(data.freelancer_commission_fixed);
                
               
               
               
               
                $('#task-accounting-modal').modal('show');
                 
            }
                            
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





</script>


  

@endsection