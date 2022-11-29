@extends('layouts.vertical', ['title' =>  'Managers' ])

@section('css')
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- for File Upload -->

    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet"  />
    <link rel="stylesheet" href="{{ asset('telinput/css/intlTelInput.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('telinput/css/demo.css') }}" type="text/css">
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
                <h4 class="page-title">{{__("Managers")}}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-sm-left">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <span>{!! \Session::get('success') !!}</span>
                            </div>
                        @endif
                    </div>
                    @php
                        $warehouse_mode = checkWarehouseMode();
                    @endphp
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            @if($warehouse_mode['show_warehouse_module'] == 1)
                                <div class="col-sm-12">
                                    <form method="get" id="search_manager" class="form-inline">
                                        <div class="form-group">
                                            <label for="manager_type">Manager Type</label>&nbsp;&nbsp;&nbsp;
                                            {{-- @dd(app('request')->input('manager_type')) --}}
                                            <select name="manager_type" id="manager_type" class="form-control" onchange="submitForm();">
                                                <option value="" @if (app('request')->input('manager_type') == "") {{'selected="selected"'}} @endif>All</option>
                                                <option value="0" @if (app('request')->input('manager_type') != null && app('request')->input('manager_type') == 0) {{'selected="selected"'}} @endif>Manager</option>
                                                <option value="1" @if (app('request')->input('manager_type') == 1) {{'selected="selected"'}} @endif>Warehouse Manager</option>
                                            </select>
                                        </div>&nbsp;&nbsp;&nbsp;
                                        <div class="form-group">
                                            <label for="warehouse">Warehouse</label>&nbsp;&nbsp;&nbsp;
                                            {{-- @dd(app('request')->input('manager_type')) --}}
                                            <select name="warehouse" id="warehouse" class="form-control" onchange="submitForm();">
                                                <option value="" @if (app('request')->input('warehouse') == "") {{'selected="selected"'}} @endif>All</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{$warehouse->id}}" @if (app('request')->input('warehouse') == $warehouse->id) {{'selected="selected"'}} @endif>{{$warehouse->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-4 text-right btn-auto">
                            <a class="btn btn-blue waves-effect waves-light text-sm-right"
                                href="{{route('subadmins.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Manager")}}</a>
                            {{-- <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Sub Admin</button> --}}
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100" id="">
                            <thead>
                                <tr>                                    
                                    <th>{{__("Name")}}</th>
                                    <th>{{__('Email')}}</th>
                                    <th>{{__("Phone")}}</th>
                                    @if($warehouse_mode['show_warehouse_module'] == 1)
                                        <th>{{__("Manager Type")}}</th>
                                        <th>{{__("Warehouses")}}</th>
                                    @endif
                                    <th>{{__("Status")}}</th> 
                                    <th>{{__("Action")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!@empty($subadmins) && $subadmins->count() > 0)
                                @foreach ($subadmins as $singleuser)
                                    <tr> 
                                        <td>
                                            {{ $singleuser->name }}
                                        </td>
                                        <td>
                                            {{ $singleuser->email }}
                                        </td>
                                        <td>
                                            @if(!empty($singleuser->dial_code)) +{{ $singleuser->dial_code }} @endif {{ $singleuser->phone_number }}
                                        </td>
                                        @if($warehouse_mode['show_warehouse_module'] == 1)
                                            <td>
                                                @if($singleuser->manager_type == 1)
                                                    {{ ('Warehouse Manager') }}
                                                @else
                                                    {{ ('Manager') }}
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $warehouses = implode(',', $singleuser->warehouse->pluck('name')->toArray());
                                                @endphp
                                                @if(empty($warehouses))
                                                    {{ ('-') }}
                                                @else
                                                    {{ $warehouses }}
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            {{ ($singleuser->status==1)?__("Active"):__("Inactive") }}
                                        </td>                                    
                                        <td>
                                            <div class="form-ul" style="width: 60px;">
                                                <div class="inner-div"> <a href1="#" href="{{route('subadmins.edit', $singleuser->id)}}"  class="action-icon editIconBtn"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                                {{-- <div class="inner-div">
                                                    <form method="POST" action="{{route('subadmins.destroy', $singleuser->id)}}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete"></i></button>

                                                        </div>
                                                    </form>
                                                </div> --}}
                                            </div>                                        
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center text-danger" colspan="7">no record found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $subadmins->links() }}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>

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
    <script src="{{ asset('telinput/js/intlTelInput.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
    <script>
        function submitForm(){
            // Call submit() method on <form id='myform'>
            document.getElementById('search_manager').submit(); 
        }
    </script>
@endsection