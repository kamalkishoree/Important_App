@extends('layouts.vertical', ['title' =>  'Warehouse' ])
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-md-8">
                <div class="page-title-box">
                    @if(isset($warehouse))
                        <h4 class="page-title">{{__("Update Warehouse")}}</h4>
                    @else
                        <h4 class="page-title">{{__("Create Warehouse")}}</h4>
                    @endif
                </div>
            </div>
            <div class="col-sm-4 text-right btn-auto">
                <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Amenities")}}</button>
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
                        @if(isset($warehouse))
                            <form id="UpdateWarehouse" method="post" action="{{route('warehouse.update', $warehouse->id)}}" enctype="multipart/form-data">
                            @method('PUT')
                        @else
                            <form id="StoreWarehouse" method="post" action="{{route('warehouse.store')}}" enctype="multipart/form-data">
                        @endif
                        @csrf
                        <input type="hidden" name="warehouse_id" value="{{$warehouse->id??''}}">
                        <div class=" row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">{{__('Name')}}</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $warehouse->name ?? '')}}" placeholder="Enter name" required>
                                    @if($errors->has('name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="control-label">{{__("Code")}}</label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $warehouse->code ?? '')}}" placeholder="Enter code" required>
                                    @if($errors->has('code'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address" class="control-label">{{__("Address")}}</label>
                                    <input type="tel" name="address" class="form-control" value="{{ old('address', $warehouse->address ?? '')}}"id="address" placeholder="Enter address">
                                    @if($errors->has('address'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="check_label">
                                        <label for="amenities" class="control-label">{{__("Amenities")}}</label>
                                    </div>
                                    @php
                                        $amenity = [];
                                        if(!empty($warehouse->amenity)){
                                            $amenity = $warehouse->amenity->pluck('id')->toArray();
                                        }
                                    @endphp
                                    @if(!empty($amenities) && $amenities->count() > 0)
                                        @foreach ($amenities as $ameniti)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="amenities[]" value="{{$ameniti->id}}" @if(in_array($ameniti->id, $amenity)) checked @endif>&nbsp;&nbsp;{{$ameniti->name}}
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="control-label">{{__("Category")}}</label>
                                    <select name="category" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach ($category as $cat)
                                            <option value="{{$cat->id}}" @if(!empty($warehouse->category_id) && $warehouse->category_id == $cat->id) selected @endif>{{$cat->slug}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('category'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('category') }}</strong>
                                        </span>
                                    @endif                                        
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2 mt-2">
                            <div class="col-12">
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-blue btn-block" type="submit"> {{__("Submit")}} </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include('warehouse.warehouse-modal')
@section('script')
    @include('warehouse.warehouse-script')
@endsection
