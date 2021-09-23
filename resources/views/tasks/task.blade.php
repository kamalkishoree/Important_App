@extends('layouts.vertical', ['title' => __('Route')])

@section('css')
@endsection
@php
use Carbon\Carbon;
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
   .agents-datatable th, .agents-datatable td{
    padding: 0.85rem !important;
    }
    .address_box_lable {
        width: 60% !important;
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
                    <h4 class="page-title">{{__("Routes")}}</h4>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="total_earnings_by_vendors">{{ $panding_count }}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{__("Pending Assignment")}}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-store-24-hour text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="total_order_count">{{$active_count}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{__("Active") . ' Orders'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_cash_to_collected">{{$employeesCount}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{__("Active") . ' Customer'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_delivery_fees">{{$agentsCount}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{__("Active") . ' Agents'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                                <label for="teacher">{{__("Pending Assignment")}}<span
                                                        class="showspan">{{ ' (' . $panding_count . ')' }}</span></label>
                                            </li>
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="student" onclick="handleClick(this);" name="status"
                                                    value="assigned" {{ $status == 'assigned' ? 'checked' : '' }}>
                                                <label for="student">{{__("Active")}}<span
                                                        class="showspan">{{ ' (' . $active_count . ')' }}</span></label>
                                            </li>


                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="parent" name="status" onclick="handleClick(this);"
                                                    value="completed" {{ $status == 'completed' ? 'checked' : '' }}>
                                                <label for="parent">{{__("History")}}<span
                                                        class="showspan">{{ ' (' . $history_count . ')' }}</span></label>
                                            </li>

                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="failed" name="status" onclick="handleClick(this);"
                                                    value="failed" {{ $status == 'failed' ? 'checked' : '' }}>
                                                <label for="failed">{{__("Failed")}}<span
                                                        class="showspan">{{ ' (' . $failed_count . ')' }}</span></label>
                                            </li>

                                        </ul>
                                    </div>
                                </form>
                            </div>
                            <!-- @if (isset($status) && $status == 'unassigned' && $panding_count != 0 ) -->
                                <div class="col-sm-4 text-right assign-toggle assign-show ">
                                    <button type="button" class="btn btn-info assign_agent" data-toggle="modal" data-target="#add-assgin-agent-model" data-backdrop="static" data-keyboard="false">{{__("Assign")}}</button> 
                                    <button type="button" class="btn btn-info assign_date" data-toggle="modal" data-target="#add-assgin-date-model" data-backdrop="static" data-keyboard="false">{{__("Change Date")}}/{{__("Time")}}</button> 
                                </div>
                            <!-- @endif -->
                        </div>
                        <input type="hidden" id="routes-listing-status" value="unassigned">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100 agents-datatable display nowrap" id="agents-datatable">
                                <thead>
                                    <tr>
                                        @if (!isset($status) || $status == 'unassigned')
                                        <th><input type="checkbox" class="all-driver_check" name="all_driver_id" id="all-driver_check"></th>
                                        @endif
                                        <th>{{__("Customer")}}</th>
                                        <th>{{__("Phone.No")}}</th>
                                        <th>{{__("Driver")}}</th>
                                        <th>{{__("Due Time")}}</th>
                                        <th>{{__("Routes")}}</th>
                                        <th>{{__("Tracking Url")}}</th>
                                        <th>{{__("Route Proofs")}}</th>
                                        <th>{{__("Pricing")}}</th>
                                        <th style="width: 85px;">{{__("Action")}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
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
    @include('modals.task-proofs')
    @include('modals.assgin_task_agent')
    @include('modals.assgin_task_date')
@endsection

@section('script')
    <script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    @include('tasks.taskpagescript')


    
@endsection
