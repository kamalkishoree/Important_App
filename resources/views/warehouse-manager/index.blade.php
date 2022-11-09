@extends('layouts.vertical', ['title' =>  'Warehouse Manager' ])
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{__("Warehouse Manager")}}</h4>
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
                                
                            </div>
                            <div class="col-sm-4 text-right btn-auto">
                                <a class="btn btn-blue waves-effect waves-light text-sm-right" href="{{route('warehouse-manager.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Warehouse Manager")}}</a>
                            </div>
                            <div class="col-sm-12">
                                <div class="text-sm-left">
                                    @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <span>{!! \Session::get('success') !!}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="">
                                <thead>
                                    <tr>
                                        <th>{{__("#")}}</th>
                                        <th>{{__("Name")}}</th>
                                        <th>{{__('Email')}}</th>
                                        <th>{{__("Phone Number")}}</th>
                                        <th>{{__("Warehouses")}}</th>
                                        <th>{{__("Created Date")}}</th>
                                        <th>{{__("Action")}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($warehouse_manager) && $warehouse_manager->count() > 0)
                                        @foreach ($warehouse_manager as $manager)
                                            <tr>
                                                <td>{{$loop->iteration}}</td> 
                                                <td>{{ $manager->name }}</td>
                                                <td>{{ $manager->email }}</td>
                                                <td>{{ $manager->phone_number }}</td>
                                                <td>
                                                    @php
                                                        $warehouses = implode(',', $manager->warehouse->pluck('name')->toArray());
                                                    @endphp
                                                    {{ $warehouses }}
                                                </td>
                                                <td>{{ formattedDate($manager->created_at) }}</td>                                    
                                                <td>
                                                    <div class="form-ul" style="width: 60px;">
                                                        <div class="inner-div"> <a href1="#" href="{{route('warehouse-manager.edit', $manager->id)}}"  class="action-icon editIconBtn"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                                        <div class="inner-div">
                                                            <form method="POST" action="{{route('warehouse-manager.destroy', $manager->id)}}">
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
                                        @else
                                        <tr>
                                            <td colspan="8" class="text-center text-danger">no record found..</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination pagination-rounded justify-content-end mb-0">
                            {{ $warehouse_manager->links() }}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>
@endsection