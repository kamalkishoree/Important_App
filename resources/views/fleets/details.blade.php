@extends('layouts.vertical', ['title' => 'Fleets Details' ])
@section('content')
<div class="container-fluid">
    @csrf
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Fleets Details') }} </h4>
            </div>
        </div>
    </div>

    <!-- end page title -->
    <div class="row">
            @if (\Session::has('success'))
            <div class="col m-2 alert alert-success alert-dismissible fade show" role="alert">
                    <span>{!! \Session::get('success') !!}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="alFilterLocation">
                        <ul class="p-0 d-flex justify-content-end">
                                <li class="d-flex">
                                    <a href="{{route('fleet.index')}}" type="button" class="btn btn-blue waves-effect waves-light mr-1">{{__("Manage Fleets")}}</a>
                                </li>
                            </ul>
                    </div>
                  
                    <div class="tab-content nav-material pt-0" id="top-tabContent">
                        <div class="tab-pane fade past-order show active" id="active_vendor" role="tabpanel" aria-labelledby="active-vendor">

                            <div class="table-responsive nagtive-margin">
                                <table class="table table-striped dt-responsive nowrap w-100 all agent-listing" id="agent-listing">
                                    <thead>
                                        <tr>
                                            <th class="sort-icon">{{__("Order No.")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Created At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Driver Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Registration Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total km/miles")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Model")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Make")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @forelse($orders as $order)
                                            <tr>
                                                <td><a href="javascript:;" class="orderDetail" data-id="{{$order->id}}">#{{$order->order_number}}</a></td>
                                                <td>{{$order->created_at}}</td>
                                                <td>{{@$order->agent->name}}</td>
                                                <td><a href="javascript:;" class="fleetDetail" data-id="{{$order->fleet->id}}">{{$order->fleet->registration_name}}</a></td>
                                                <td>{{$order->actual_distance}}</td>
                                                <td>{{$order->fleet->model}}</td>
                                                <td>{{$order->fleet->make}}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="10"><p class="text-center">No record Found</p></td>
                                            </tr>
                                       @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
                
            </div> <!-- end col -->
        </div>
    </div>
</div>

@include('fleets.modals')
@include('fleets.pagescript')
@endsection
