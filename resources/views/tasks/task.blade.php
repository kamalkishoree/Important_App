@extends('layouts.vertical', ['title' => 'Task'])

@section('css')
@endsection
@php
use Carbon\Carbon;
@endphp
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
                                <form name="getTask" id="getTask" method="get" action="{{ route('tasks.index') }}">
                                    <div class="login-form">
                                        <ul class="list-inline">
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="teacher" name="status" onclick="handleClick(this);"
                                                    value="unassigned" {{ $status == 'unassigned' ? 'checked' : '' }}>
                                                <label for="teacher">Pending Allocation<span
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
                            <div class="col-sm-2"></div>
                            <!-- @if (isset($status) && $status == 'unassigned' && $panding_count != 0 ) -->
                                <div class="col-sm-4 text-right assign-toggle assign-show ">
                                <button type="button" class="btn btn-info assign_agent" data-toggle="modal" data-target="#add-assgin-agent-model" data-backdrop="static" data-keyboard="false">Assign</button> 
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
                                        {{-- <th>Create Time</th> --}}
                                        <th>Due Time</th>
                                        <th>Tasks</th>
                                        <th>Tracking Url</th>
                                        <th>Task Proofs</th>
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
                                                {{ $task->customer->name }}
                                            </td>
                                            <td>
                                                {{ $task->customer->phone_number }}
                                            </td>
                                            <td>
                                                {{ empty($task->agent) ? 'Unassigned' : $task->agent->name }}
                                            </td>
                                            {{-- <td>
                                                @php
                                                    $create = Carbon::createFromFormat('Y-m-d H:i:s', $task->created_at, 'UTC');
                                                    $create->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                                    $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                                                    
                                                @endphp
                                               {{date(''.$preference->date_format.' '.$timeformat.'', strtotime($create))}}

                                            </td> --}}
                                            <td>
                                                @php
                                                    $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                                                    $order = Carbon::createFromFormat('Y-m-d H:i:s', $task->order_time, 'UTC');
                                                    $order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                                @endphp
                                                {{date(''.$preference->date_format.' '.$timeformat.'', strtotime($order))}}

                                            </td>
                                            <td>
                                                <button class="showtasks" value="{{ $task->id }}"><i
                                                        class="fe-eye"></i></button>
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
                                                        <form method="POST"
                                                            action="{{ route('tasks.destroy', $task->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="form-group">
                                                                <button type="submit"
                                                                    class="btn btn-primary-outline action-icon"> <i
                                                                        class="mdi mdi-delete"></i></button>

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
@endsection

@section('script')
    <script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    {{-- <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script> --}}
    @include('tasks.taskpagescript')
@endsection
