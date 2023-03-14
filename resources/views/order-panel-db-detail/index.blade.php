@extends('layouts.vertical', ['title' =>  'Order Panel DB Detail' ])
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{__("Order Panel DB Detail")}}</h4>
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
                                <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Order DB")}}</button>
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
                                        <th>{{__("Action")}}</th>
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
                                                <td>
                                                    <div class="form-ul" style="width: 60px;">
                                                        

                                                        <div class="inner-div"> <a href="javascript:void(0);" class="action-icon editIconBtn" data-name="{{$data->name}}" data-url="{{$data->url}}" data-code="{{$data->code}}" data-key="{{$data->key}}" data-id="{{$data->id}}"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                                        {{-- <div class="inner-div">
                                                            <form method="POST" action="{{route('order-panel-db.destroy', $data->id)}}">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('order-panel-db-detail.order-panel-script')
@endsection