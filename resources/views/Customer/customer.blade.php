@extends('layouts.vertical', ['title' => 'Customers'])

@section('css')
<style>
    .table th,.table td, .table td {
        display: table-cell !important;
    }
</style>
@endsection
@php
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title page-title1">{{ __("Customers") }}</h4>
            </div>
        </div>
    </div>
    <div class="row custom-cols">
        <div class="col col-md-4 col-lg-3 col-xl">
            <div class="widget-rounded-circle card">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="text-end">
                                <p class="text-muted mb-1 text-truncate"> {{ 'Total '. __("Customers") }}</p>
                                <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_rejected_order">{{$customersCount}}</span></h3>
                            </div>
                        </div>
                        <div class="col-4 text-md-right">
                            <div class="avatar-lg rounded-circle ml-auto">
                                <i class="fe-heart font-22 avatar-title"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-md-4 col-lg-3 col-xl">
            <div class="widget-rounded-circle card">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                    <div class="col-8">
                            <div class="text-end">
                                <p class="text-muted mb-1 text-truncate">Active</p>
                                <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_active_order">{{$activeCustomers}}</span></h3>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="avatar-lg rounded-circle ml-auto">
                                <i class="fe-shopping-cart font-22 avatar-title"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-md-4 col-lg-3 col-xl">
            <div class="widget-rounded-circle card">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="text-end">
                                <p class="text-muted mb-1 text-truncate">In-Active</p>
                                <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_delivered_order">{{$inActiveCustomers}}</span></h3>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="avatar-lg rounded-circle ml-auto">
                                <i class="fe-bar-chart-line font-22 avatar-title"></i>
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
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Customer")}}</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100"  id="pricing-datatable">
                            <thead>
                                <tr>
                                    <th>{{__("Name")}}</th>
                                    <th>{{("Email")}}</th>
                                    <th>{{__("Phone number")}}</th>
                                    <th>{{__("Status")}}</th>
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
@include('Customer.customer-modal')

@endsection

@section('script')
    <script src="{{asset('assets/js/storeAgent.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
@include('Customer.pagescript')  

@endsection