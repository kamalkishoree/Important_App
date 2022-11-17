@extends('layouts.vertical', ['title' =>  'Warehouse' ])
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{__("Warehouse")}}</h4>
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
                                @if(Auth::user()->manager_type != 1)
                                    <a class="btn btn-blue waves-effect waves-light text-sm-right" href="{{route('warehouse.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Warehouse")}}</a>
                                @endif
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
                                        <th>{{__('Code')}}</th>
                                        <th>{{__("Address")}}</th>
                                        <th>{{__("Latitude")}}</th>
                                        <th>{{__("Longitude")}}</th>
                                        <th>{{__("Amenities")}}</th>
                                        <th>{{__("Category")}}</th>
                                        <th>{{__("Created Date")}}</th>
                                        <th>{{__("Action")}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($warehouses) && $warehouses->count() > 0)
                                        @foreach ($warehouses as $warehouse)
                                            <tr>
                                                <td>{{$loop->iteration}}</td> 
                                                <td>{{ $warehouse->name }}</td>
                                                <td>{{ $warehouse->code }}</td>
                                                <td style="width:300px;min-width:300px;">{{ $warehouse->address }}</td>
                                                <td>{{ $warehouse->latitude }}</td>
                                                <td>{{ $warehouse->longitude }}</td>
                                                <td>
                                                    @php
                                                        $amenity = implode(',', $warehouse->amenity->pluck('name')->toArray());
                                                    @endphp
                                                    {{ $amenity }}
                                                </td>
                                                <td style="width:300px;max-width: 300px;min-width: 300px;">
                                                    @php
                                                        $category = implode(',', $warehouse->category->pluck('slug')->toArray());
                                                    @endphp
                                                    {{ $category }}
                                                </td>                                    
                                                <td>{{ formattedDate($warehouse->created_at) }}</td>                                    
                                                <td>
                                                    <div class="form-ul" style="width: 60px;">
                                                        <div class="inner-div"> <a href1="#" href="{{route('warehouse.edit', $warehouse->id)}}"  class="action-icon editIconBtn"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                                        <div class="inner-div">
                                                            <form method="POST" action="{{route('warehouse.destroy', $warehouse->id)}}">
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
                                            <td colspan="10" class="text-center text-danger">no record found..</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination pagination-rounded justify-content-end mb-0">
                            @if(!empty($warehouses))
                                {{ $warehouses->links() }}
                            @endif
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>
@endsection