@extends('layouts.vertical', ['title' => 'Pricing Rules'])

@section('css')
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />


    <!-- for File Upload -->

    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/css/intlTelInput.css'>
    <link href="{{ asset('assets/libs/nestable2/nestable2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        // workaround
        .intl-tel-input {
            display: table-cell;
        }

        .inner-div {
            width: 50%;
            float: left;
        }

        .intl-tel-input .selected-flag {
            z-index: 4;
        }

        .intl-tel-input .country-list {
            z-index: 5;
        }

        .input-group .intl-tel-input .form-control {
            border-top-left-radius: 4px;
            border-top-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 0;
        }

    </style>
@endsection

@section('content')
    <!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Pricing Rules</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">

        {{-- <div class="col-md-2 col-12">
            <div class="card-box">
                <div class="">
                    <div class="" id="nestable_list_1" style="display: none">
                    </div>
                </div><!-- end col -->

                <div class="">
                    <h4 class="header-title mt-3 mt-md-0 small-mt-0">Set Priority</h4>
                    <div class="custom-dd dd" id="nestable_list_2">
                        @if(!empty($priority))
                        <ol class="dd-list" id="priority">
                            <li class="dd-item" data-id="{{ $priority->first }}">
                                <div class="dd-handle">
                                    {{ $priority->first }}
                                </div>
                            </li>

                            <li class="dd-item" data-id="{{ $priority->second }}">
                                <div class="dd-handle">
                                    {{ $priority->second }}
                                </div>
                            </li>
                            <li class="dd-item" data-id="{{ $priority->third }}">
                                <div class="dd-handle">
                                    {{ $priority->third }}
                                </div>
                            </li>
                            <li class="dd-item" data-id="{{ $priority->fourth }}">
                                <div class="dd-handle">
                                    {{ $priority->fourth }}
                                </div>
                            </li>
                        </ol>
                        @endif
                    </div>
                </div> <!-- end col -->
                

            </div> <!-- end card-box -->
        </div> --}}
        
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
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Pricing Rules</button>

                            <!--<a href="{{ route('pricing-rules.create') }}"
                                class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>
                                Add Pricing Rules</a> -->
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100" id="pricing-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Base Price</th>
                                    <th>Base Duration</th>
                                    <th>Base Distance</th>
                                    <th>Base Waiting</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pricing as $price)
                                    <tr>
                                        <td>
                                            {{ $price->name }}
                                        </td>
                                        <td>
                                            {{ $price->base_price }}
                                        </td>
                                        <td>
                                            {{ $price->base_duration }}
                                        </td>
                                        <td>
                                            {{ $price->base_distance }}
                                        </td>
                                        <td>
                                            {{ $price->base_waiting }}
                                        </td>
                                        

                                        <td>
                                            <div class="form-ul" style="width: 60px;">
                                                <div class="inner-div"> <a href="#" href1="{{ route('pricing-rules.edit', $price->id) }}" class="action-icon editIcon" priceId="{{$price->id}}"> <i class="mdi mdi-square-edit-outline"></i></a> 
                                                </div>
                                                <div class="inner-div">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
</div>

@include('pricing-rules.price-modal')

@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.js"></script>

<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script> 
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>

<script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script src="{{ asset('assets/js/storeAgent.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.7/js/intlTelInput.js"></script>

<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/libs/nestable2/nestable2.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/nestable.init.js') }}"></script>

<script>

    $(document).on('click', '.selectpicker', function( event ) {
        event.stopPropagation();
    }); 


    $(document).ready(function() {
        $('#pricing-datatable').DataTable();
    });

    function runPicker(){
        $('.datetime-datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });

        $('.selectpicker').selectpicker();
    }

    $('.openModal').click(function(){
        $('#add-pricing-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        runPicker();
    });

    $(".editIcon").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
       
        var pid = $(this).attr('priceId');

        $.ajax({
            type: "get",
            url: "<?php echo url('pricing-rules'); ?>" + '/' + pid + '/edit',
            data: '',
            dataType: 'json',
            success: function (data) {

                console.log('data');
        
                $('.datetime-datepicker').flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d H:i"
                });

                $('#edit-price-modal #editCardBox').html(data.html);
                $('#edit-price-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                runPicker();
                ('.custom-switch').switch();

            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.submitEditForm', function(){ console.log('ad');
        document.getElementById("edit_price").submit();
    });

    !function($) {
        "use strict";
        
        var Nestable = function() {};
        Nestable.prototype.updateOutput = function(e) {
                var list = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                   output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            },
            //init
            Nestable.prototype.init = function() {
                
                $('#nestable_list_2').nestable({
                    group: 1
                }).on('change', this.updateOutput);

                // output initial serialised data                
                this.updateOutput($('#nestable_list_2').data('output', data));

                $('#nestable_list_menu').on('click', function(e) {
                    var target = $(e.target),
                        action = target.data('action');
                    if (action === 'expand-all') {
                        $('.dd').nestable('expandAll');
                    }
                    if (action === 'collapse-all') {
                        $('.dd').nestable('collapseAll');
                    }
                });

                $('#nestable_list_3').nestable();
            },
            //init
            $.Nestable = new Nestable, $.Nestable.Constructor = Nestable
    }(window.jQuery),

    //initializing 
    function($) {
        "use strict";
        $.Nestable.init()
    }(window.jQuery);

</script>

@endsection
