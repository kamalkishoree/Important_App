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

                        <div class="col-sm-6">
                        <form name="getTask" id="getTask" method="get" action="{{route('tasks.index')}}">
                            <div class="login-form">
                            <ul class="list-inline">
                                <li class="d-inline-block mr-2">
                                  <input type="radio" id="student" onclick="handleClick(this);" name="animal" value="" checked>
                                  <label for="student">All</label>
                                </li>
                                <li class="d-inline-block mr-2">
                                  <input type="radio" id="teacher" name="animal" onclick="handleClick(this);">
                                  <label for="teacher">Pending</label>
                                </li>
                  
                                <li class="d-inline-block mr-2">
                                  <input type="radio" id="parent" name="animal" value="" onclick="handleClick(this);">
                                  <label for="parent">History</label>
                                </li>
                              </ul>
                            </div>
                                {{-- <div class="d-inline-block mr-3">
                                    <input type="radio" name="status" onclick="handleClick(this);" id="radio1" value="all" {{(!isset($status) || $status == 'all') ? 'checked' : ''}}>
                                    <label for="radio1">All</label>
                                </div>
                                <div class="d-inline-block mr-3">
                                    <input type="radio" name="status" onclick="handleClick(this);" id="radio2" value="pending" {{(isset($status) && $status == 'pending') ? 'checked' : ''}}>
                                    <label for="radio2">Pending</label>
                                </div>
                                
                                <div class="d-inline-block mr-3">
                                    <input type="radio" name="status" onclick="handleClick(this);" id="radio3" value="active" {{(isset($status) && $status == 'active') ? 'checked' : ''}}>
                                    <label for="radio3">Active</label>
                                </div>
                               <div class="d-inline-block">
                                <input type="radio" name="status" onclick="handleClick(this);" id="radio4" value="completed" {{(isset($status) && $status == 'completed') ? 'checked' : ''}}>
                                <label for="radio4">Completed</label>
                               </div> --}}

                        </form>
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4 text-right">
                            <!--<button type="button" class="btn btn-blue waves-effect waves-light showTaskPop" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Task</button> -->
                         <a href="{{ route('tasks.create') }}" class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i> Add Task</a>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100"  id="agents-datatable">
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Customer</th>
                                    {{-- <th>Order Id</th> --}}
                                    <th>Driver</th>
                                    <th>Create Time</th>
                                    <th>Pricing Rule</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr>
                                    
                                    <td>
                                        {{$task->id}}
                                    </td>
                                    <td>
                                        {{$task->customer->name}}
                                    </td>
                                    {{-- <td>
                                        {{$task->order->id}}
                                    </td> --}}
                                    <td>
                                        {{ empty($task->agent) ? 'Unassigned' : $task->agent->name }}
                                    </td>
                                    <td>
                                        {{$task->created_at}}
                                    </td>
                                    <td>
                                        Not Alloted
                                    </td>

                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div"> <a href1="#" href="{{route('tasks.edit', $task->id)}}"  class="action-icon editIconBtn"> <i class="mdi mdi-square-edit-outline"></i></a></div>
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
@endsection

@section('script')

<!-- Plugins js-->
  
<script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
<script src="{{asset('assets/js/storeAgent.js')}}"></script>
<!-- for File Upload -->
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.7/js/intlTelInput.js"></script>
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>

<script>

/*$('.showTaskPop').click(function(){ 
    console.log('top');
    var src1 = "{{url('tasks/create')}}";
    $('#add-task-modal .modal-title').html('Add Task');
    $('#add-task-modal #editCardBox').html('<iframe id="iframe" src="'+src1+'" style="width:100%; height:700px;"></iframe>');

    $('#add-task-modal').modal({
            //backdrop: 'static',
            keyboard: false
    });
});

$('.editIconBtn').click(function(){ 
    console.log('top');
    var src1 = #(this).attr('href1');
    $('#add-task-modal .modal-title').html('Edit Task');
    $('#add-task-modal #editCardBox').html('<iframe id="iframe" src="'+src1+'" style="width:100%; height:700px;"></iframe>');

    $('#add-task-modal').modal({
            backdrop: 'static',
            keyboard: false
    });
});*/

$(document).ready( function () {
    $('#agents-datatable').DataTable();
});

    function handleClick(myRadio) {
        $('#getTask').submit();
    }
/*
$("#phone_number").intlTelInput({
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"
});
$('.intl-tel-input').css('width','100%');

var regEx = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
$("#addAgent").bind("submit", function() {
       var val = $("#phone_number").val();
       if (!val.match(regEx)) {
            $('#phone_number').css('color','red');
            return false;
        }
});

$(function(){
    $('#phone_number').focus(function(){
        $('#phone_number').css('color','#6c757d');
    });
});

$(document).ready( function () {
    $('#basic-datatable').DataTable();
});


$("#phone_number").inputFilter(function(value) {
  return /^-?\d*$/.test(value); 
});*/

</script>

@endsection