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

            <div class="col-2">
                <div class="card-box">
                    <div class="">
                        <div class="" id="nestable_list_1" style="display: none">
                        </div>
                    </div><!-- end col -->

                    <div class="">
                        <h4 class="header-title mt-3 mt-md-0">Set Priorty</h4>
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
            </div>
            
            <div class="col-10">
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
                                <a href="{{ route('pricing-rules.create') }}"
                                    class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>
                                    Add Pricing Rules</a>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="pricing-datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
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
                                                {{ $price->start_date_time }}
                                            </td>
                                            <td>
                                                {{ $price->end_date_time }}
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
                                            <!-- <td>
                                                            <span class="badge bg-soft-success text-success">Active</span>
                                                        </td> -->

                                            <td>
                                                <div class="form-ul" style="width: 60px;">
                                                    <div class="inner-div"> <a
                                                            href="{{ route('pricing-rules.edit', $price->id) }}"
                                                            class="action-icon"> <i
                                                                class="mdi mdi-square-edit-outline"></i></a></div>
                                                    <div class="inner-div">
                                                        <form method="POST"
                                                            action="{{ route('pricing-rules.destroy', $price->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="form-group">
                                                                <button type="submit"
                                                                    class="btn btn-primary-outline action-icon"> <i
                                                                        class="mdi mdi-delete"></i></button>

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

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
            


        </div>
    @endsection

    @section('script')

        <!-- Plugins js-->
        <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
        <!-- Page js-->



        <script src="{{ asset('assets/js/storeAgent.js') }}"></script>

        <!-- for File Upload -->
        <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
        <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
        <!-- Page js-->
        <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.7/js/intlTelInput.js"></script>

        <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('assets/libs/nestable2/nestable2.min.js') }}"></script>

        <!-- Page js-->
        <script src="{{ asset('assets/js/pages/nestable.init.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('#pricing-datatable').DataTable();
            });





            ! function($) {
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
                        // activate Nestable for list 1
                        // $('#nestable_list_1').nestable({
                        //     group: 1
                        // }).on('change', this.updateOutput);

                        // activate Nestable for list 2
                        $('#nestable_list_2').nestable({
                            group: 1
                        }).on('change', this.updateOutput);

                        // output initial serialised data
                        // this.updateOutput($('#nestable_list_1').data('output', $('#nestable_list_1_output')));
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
