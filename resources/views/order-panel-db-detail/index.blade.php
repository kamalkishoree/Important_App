<?php
use Illuminate\Support\Facades\Session;

?>
 @if (Route::currentRouteName() == 'inventory-panel-db')
  @php  $title = "Inventory Panel ";  @endphp
 @else
 @php  $title = "Order Panel ";  @endphp
 @endif
@extends('layouts.vertical', ['title' =>  $title  ])
@section('content')


    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    
                @if (Route::currentRouteName() == 'inventory-panel-db')
                 <h4 class="page-title">{{__("Inventory Panel")}}</h4>
                 @else
                <h4 class="page-title">{{__("Order Panel ")}}</h4>
                @endif

                
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
                                <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> 
                             
                                @if (Route::currentRouteName() == 'inventory-panel-db')
                                {{__("Add Inventory Detail")}}
                                @else
                                {{__("Add Order Detail")}}
                                @endif

                            
                            </button>
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
                                        <th>{{__('Url')}}</th>
                                        <th>{{__("Code")}}</th>
                                        <th>{{__("Key")}}</th>
                                        <th>{{__("Created Date")}}</th>
                                        <th>{{__("Type")}}</th>
                                        <th>{{__("Sync Data")}}</th>
                                        <th>{{__("Action")}}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Order_panel_Data) && $Order_panel_Data->count() > 0)
                                        @foreach ($Order_panel_Data as $data)
                                            <tr>
                                                <td>{{$loop->iteration}}</td> 
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->url }}</td>
                                                <td>{{ $data->code }}</td>
                                                <td>{{ $data->key }}</td> 
                                                <td>{{ formattedDate($data->created_at) }}</td> 
                                                @if($data->type == 0)
                                                
                                                <td>Order Panel</td>   
                                                @else
                                                <td>Inventory Panel</td>
                                                @endif       
                                                <td>

                                                <form action="{{route('category.importOrderSideCategory')}}" method="post">
                                                @csrf
                                                    <input type="hidden" name="order_panel_id" value="{{ $data->id}}">
                                                    <button type="submit" class="ml-2 border-0" ><i class="fa fa-sync"></i></button>
                                                </form>

                                                </td>     
                                                                             
                                                                             
                                                <td>
                                                    <div class="form-ul" style="width: 60px;">
                                                        

                                                      <div class="inner-div"> <a href="javascript:void(0);" class="action-icon editIconBtn" data-name="{{$data->name}}" data-url="{{$data->url}}" data-code="{{$data->code}}" data-key="{{$data->key}}" data-type="{{$data->type}}" data-id="{{$data->id}}"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                                        
                                                      
                                                        <div class="inner-div">
                                                            <form method="POST" action="{{route('order-panel-db.destroy', $data->id)}}">
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
                            @if(!empty($Order_panel_Data))
                                {{ $Order_panel_Data->links() }}
                            @endif
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>
@endsection
@include('order-panel-db-detail.order-panel-modal')


@section('script')
    @include('order-panel-db-detail.order-panel-script')
  
@endsection