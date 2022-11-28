@extends('layouts.vertical', ['title' => 'Products'])

@section('css')
<style>
    .table th,.table td, .table td {
        display: table-cell !important;
    }
    .footer{
        z-index: 3;
    }
    #pricing-datatable_processing {
        position: absolute !important;
        background: transparent !important;
        top: 60%;
        transform: translateY(-50%) !important;
        left: 0;
        right: 0;
        z-index: 1;
    }
    .dt-buttons.btn-group.flex-wrap {
        float: right;
        margin: 5px 0 10px 15px;
    }
    div#pricing-datatable_filter {
        padding-top: 5px;
    }
    .dataTables_filter label {
        width: 25%;
    }
    .dataTables_filter label .form-control {
        height: 37px;
        font-size: 16px;
    }
    .dt-buttons .btn.btn-secondary,.dt-buttons .btn.btn-secondary:focus,.dt-buttons .btn.btn-secondary:active {
        border-radius: 5px;
        background: #6658ddd6 !important;
    }
    .btn-label,.btn-label:focus,.btn-label:active {
        background-color: rgb(102 88 221) !important;
    }
    .dataTables_scrollHead thead th {
        cursor: pointer;
    }
    .nagtive-margin {
        margin-top: -57px;
    }
    .custom_top_bar button.btn.btn-blue {
    color: #fff;
    height: 37px;
    margin-right: 10px;
    max-width: initial;
}
.dataTables_wrapper .inner-div {
    vertical-align: bottom !important;
    margin-right: 10px;
}
.table td {
    padding: 10px 5px !important;
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
    <div class="row mb-4">
        <div class="col-6">
            <div class="page-title-box">
                <h4 class="page-title page-title1">{{ __("Products") }}</h4>
            </div>
        </div>
        <div class="col-6 text-right">
            <button type="button" class="btn btn-blue waves-effect waves-light openAddProductModal"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Product")}}</button>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body custom_body_card">
                    <div class="table-responsive nagtive-margin">
                        <table class="table table-striped dt-responsive nowrap w-100" id="product-category-datatable">
                            <thead>
                                <tr>
                                    <th>{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__("Quantity")}}</th>
                                    <th>{{__("Price")}}</th>
                                    <th>{{__("Bar Code")}}</th>
                                    <th>{{__("Status")}}</th>
                                    <th>{{__("Expiry Date")}}</th>
                                    <th>{{__("New")}}</th>
                                    <th>{{__("Featured")}}</th>
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
@endsection
@include('category.category-modal')
@section('script')
    <script src="{{asset('assets/js/storeAgent.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    @include('category.product-category-script')
@endsection