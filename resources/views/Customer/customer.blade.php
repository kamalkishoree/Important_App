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