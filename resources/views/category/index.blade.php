@extends('layouts.vertical', ['title' =>  'Category' ])
<style>
    .table th, .table td {font-size: 0.875rem;}
    .btn-auto .btn.btn-blue.waves-effect {
        height: 35px;
        margin-right: 10px;
    }
    #wrapper {
        height: unset !important;
    }
</style>
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{__("Category")}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-5"></div>
                            <div class="col-sm-7 text-right btn-auto d-flex justify-content-end">
                                <form method="get" id="db_form">
                                    <div class="form-group">
                                        <select name="db_name" id="db_name" class="form-control" style="width: 200px;margin-right: 10px;">
                                            <option value="all">All</option>
                                            @foreach ($order_db_detail as $detail)
                                                <option value="{{$detail->id}}" @if (app('request')->input('db_name') == $detail->id) {{'selected="selected"'}} @endif>{{$detail->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                                <button type="button" class="btn btn-blue waves-effect waves-light openAddProductModal"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Product")}}</button>

                                <button type="button" class="btn btn-blue waves-effect waves-light openCategoryModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Category")}}</button>
                                <form action="{{route('category.importOrderSideCategory')}}" method="post">
                                @csrf
                                    <input type="hidden" name="order_panel_id" value="{{app('request')->input('db_name') ?? 'all'}}">
                                    <button type="submit" class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Import Order Side Category")}}</button>
                                </form>
                            </div>
                            <div class="col-sm-12">
                                <div class="text-sm-left">
                                    
                                </div>
                                <div class="text-sm-left">
                                    @if (\Session::has('error'))
                                        <div class="alert alert-danger">
                                            <span>{!! \Session::get('error') !!}</span>
                                        </div>
                                    @endif
                                </div>
                                @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <span>{!! \Session::get('success') !!}</span>
                                        </div>
                                
                                @elseif(@$order_panel->sync_status && $order_panel->sync_status == 1) <!--processing-->
                                <div class="alert alert-success">
                                    <span>{{__('Category & Product Import Is Processing.')}}</span>
                                </div>
                                @endif
                                @if(@$order_panel->sync_status && $order_panel->sync_status == 2) <!--processing-->
                                <div class="alert alert-success" id="syncCompleted">
                                    <span>{{__('Category & Product Import Is Completed.')}}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="">
                                <thead>
                                    <tr>
                                        <th>{{__("#")}}</th>
                                        <th>{{__("Name")}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__("Created Date")}}</th>
                                        <th>{{__("Total Products")}}</th>
                                        <th>{{__("Action")}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($category) && $category->count() > 0)
                                        @foreach ($category as $cat)
                                            <tr>
                                                <td>{{$loop->iteration}}</td> 
                                                <td>{{ $cat->slug }}</td>
                                                <td>
                                                    @if($cat->status == 1)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">InActive</span>
                                                    @endif
                                                </td>
                                                <td>{{ formattedDate($cat->created_at) }}</td>                                    
                                                <td>{{ count($cat->products) }}</td>                                    
                                                <td>
                                                    <div class="form-ul" style="width: 60px;display: inline-flex;">
                                                        <div class="inner-div"> <a href="JavaScript:void(0);"  class="action-icon editIconBtn openEditCategoryModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false" data-name="{{ $cat->slug }}" data-id="{{ $cat->id }}" data-status="{{ $cat->status }}" style="margin-top: 5px;"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                                        {{-- <div class="inner-div"> <a href="{{route('showProduct', $cat->id)}}"  class="action-icon viewBtn" style="margin-top: 5px;"> <i class="mdi mdi-eye" title="View Products"></i></a></div> --}}
                                                        <div class="inner-div">
                                                            <form method="POST" action="{{route('category.destroy', $cat->id)}}">
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
                            @if(!empty($category))
                                {{ $category->links() }}
                            @endif
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>
@endsection
@include('category.category-modal')
@section('script')
    @include('category.category-script')
@endsection

<script>
    var sync_status = '{{$order_panel->sync_status ?? 0}}';
    if(sync_status == 1){
        setTimeout(function() {
            location.reload();
        }, 2000);
    }
    if(sync_status == 2){
        setTimeout(function() {
            $('#syncCompleted').hide();
        }, 5000);
    }
    

    
</script>