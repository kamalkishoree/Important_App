@extends('layouts.vertical', ['title' => 'Customers'])

@section('css')
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title page-title1">Customers</h4>
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
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Customer</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100"  id="pricing-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Status</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        {{$customer->name}}
                                    </td>
                                    <td>
                                        {{$customer->email}}
                                    </td>
                                    <td>
                                        {{$customer->phone_number}}
                                    </td>
                                    <td>
                                    <div class="custom-control custom-switch">
                                        <input data-id="{{$customer->id}}" type="checkbox" class="custom-control-input" id="customSwitch1" name="is_default" value="y" {{ $customer->status == 'Active' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="customSwitch1"></label>
                                    </div>
                                    </td>

                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div"> <a href="#" userId="{{$customer->id}}" class="action-icon editIcon"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{route('customer.destroy', $customer->id)}}">
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
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $customers->links() }}
                    </div>
                    <div class="row address" id="add0" style="display: none;">
                        <input type="text" id="add0-input" name="test" class="autocomplete form-control add0-input" placeholder="Address">
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
    {{-- <script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script> --}}
@include('Customer.pagescript')  

@endsection