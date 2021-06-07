@extends('layouts.vertical', ['title' => 'Route'])

@section('css')
@endsection
@php
use Carbon\Carbon;
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
   #agents-datatable th, #agents-datatable td{
    padding: 0.85rem;
    }
    #wrapper {
        overflow: auto !important;
    }
    .footer{
        z-index: 3;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Routes</h4>
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
                            <div class="col-sm-8">
                                <form name="getTask" id="getTask" method="get" action="{{ route('tasks.index') }}">
                                    <div class="login-form">
                                        <ul class="list-inline">
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="teacher" name="status" onclick="handleClick(this);"
                                                    value="unassigned" {{ $status == 'unassigned' ? 'checked' : '' }}>
                                                <label for="teacher">Pending Assignment<span
                                                        class="showspan">{{ ' (' . $panding_count . ')' }}</span></label>
                                            </li>
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="student" onclick="handleClick(this);" name="status"
                                                    value="assigned" {{ $status == 'assigned' ? 'checked' : '' }}>
                                                <label for="student">Active<span
                                                        class="showspan">{{ ' (' . $active_count . ')' }}</span></label>
                                            </li>


                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="parent" name="status" onclick="handleClick(this);"
                                                    value="completed" {{ $status == 'completed' ? 'checked' : '' }}>
                                                <label for="parent">History<span
                                                        class="showspan">{{ ' (' . $history_count . ')' }}</span></label>
                                            </li>

                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="failed" name="status" onclick="handleClick(this);"
                                                    value="failed" {{ $status == 'failed' ? 'checked' : '' }}>
                                                <label for="failed">Failed<span
                                                        class="showspan">{{ ' (' . $failed_count . ')' }}</span></label>
                                            </li>

                                        </ul>
                                    </div>
                                </form>
                            </div>
                            <!-- @if (isset($status) && $status == 'unassigned' && $panding_count != 0 ) -->
                                <div class="col-sm-4 text-right assign-toggle assign-show ">
                                    <button type="button" class="btn btn-info assign_agent" data-toggle="modal" data-target="#add-assgin-agent-model" data-backdrop="static" data-keyboard="false">Assign</button> 
                                    <button type="button" class="btn btn-info assign_date" data-toggle="modal" data-target="#add-assgin-date-model" data-backdrop="static" data-keyboard="false">Change Date/Time</button> 
                                </div>
                            <!-- @endif -->
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="agents-datatable">
                                <thead>
                                    <tr>
                                        @if (!isset($status) || $status == 'unassigned')
                                        <th><input type="checkbox" class="all-driver_check" name="all_driver_id" id="all-driver_check"></th>
                                        @endif
                                        <th>Customer</th>
                                        <th>Phone.No</th>
                                        <th>Driver</th>
                                        <th>Due Time</th>
                                        <th>Routes</th>
                                        <th>Tracking Url</th>
                                        <th>Route Proofs</th>
                                        <th>Pricing</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $task)
                                        <tr>
                                            @if (isset($status) && $status == 'unassigned')
                                            <td><input type="checkbox" class="single_driver_check" name="driver_id[]" id="single_driver" value="{{$task->id}}"></td>
                                            @endif
                                            
                                            <td>
                                                {{ (isset($task->customer->name))?$task->customer->name:'' }}
                                            </td>
                                            <td>
                                                {{ (isset($task->customer->phone_number))?$task->customer->phone_number:'' }}
                                            </td>
                                            <td>
                                                {{ empty($task->agent) ? 'Unassigned' : $task->agent->name }}
                                            </td>
                                            <td>
                                                @php
                                                    $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                                                    $order = Carbon::createFromFormat('Y-m-d H:i:s', $task->order_time, 'UTC');
                                                    
                                                    //$order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                                    $order->setTimezone($client_timezone);
                                                @endphp
                                                {{date(''.$preference->date_format.' '.$timeformat.'', strtotime($order))}}

                                            </td>
                                            <td>
                                                <?php
                                                 foreach ($task->task as $singletask) {
                                                     
                                                    if($singletask->task_type_id==1)
                                                    {
                                                        $tasktype = "Pickup";
                                                        $pickup_class = "yellow_";
                                                    }elseif($singletask->task_type_id==2)
                                                    {
                                                        $tasktype = "Dropoff";
                                                        $pickup_class = "green_";
                                                    }else{
                                                        $tasktype = "Appointment";
                                                        $pickup_class = "assign_";
                                                    }
                                                    
                                                    ?>
                                                    <div class="address_box">
                                                        <span class="{{ $pickup_class }}"> {{ $tasktype }}</span> <span class="short_name">{{ (isset($singletask->location->short_name))?$singletask->location->short_name:'' }}</span> <label data-toggle="tooltip" data-placement="bottom" title="{{ (isset($singletask->location->address))?$singletask->location->address:'' }}">{{ (isset($singletask->location->address))?$singletask->location->address:'' }}</label>
                                                    </div>
                                                     
                                                <?php } ?>
                                                

                                            </td>
                                            <td>
                                                <a onclick="window.open(this.href,'_blank');return false;" href="{{url('/order/tracking/'.Auth::user()->code.'/'.$task->unique_id.'')}}">Track</a>
                                            </td>
                                            <td>
                                                <button class="showTaskProofs btn btn-primary-outline action-icon"
                                                    value="{{ $task->id }}"><i class="fe-layers"></i></button>
                                            </td>
                                            <td>
                                                <button class="showaccounting btn btn-primary-outline action-icon setcolor"
                                                    value="{{ $task->id }}">{{ $task->order_cost }}</button>
                                            </td>
                                           
                                            <td>
                                                <div class="form-ul" style="width: 60px;">
                                                    <div class="inner-div">
                                                        <div class="set-size"> <a href1="#"
                                                                href="{{ route('tasks.edit', $task->id) }}"
                                                                class="action-icon editIconBtn"> <i
                                                                    class="mdi mdi-square-edit-outline"></i></a></div>
                                                    </div>
                                                    <div class="inner-div">
                                                        <form id="taskdelete{{$task->id}}" method="POST"
                                                            action="{{ route('tasks.destroy', $task->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="form-group">
                                                                <button type="button"
                                                                    class="btn btn-primary-outline action-icon"> <i
                                                                        class="mdi mdi-delete" taskid="{{$task->id}}"></i></button>

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
                        <div class="pagination pagination-rounded justify-content-end mb-0">
                            {{ $tasks->appends(['status'=>$status])->links() }}
                        </div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>


    </div>

    @include('modals.task-list')
    @include('modals.task-accounting')
    @include('modals.task-proofs')
    @include('modals.assgin_task_agent')
    @include('modals.assgin_task_date')
@endsection

@section('script')
    <script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    {{-- <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script> --}}
    @include('tasks.taskpagescript')


    
@endsection
