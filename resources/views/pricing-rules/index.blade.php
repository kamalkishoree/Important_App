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
                <h4 class="page-title">{{__("Pricing Rules")}}</h4>
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
                    <h4 class="header-title mt-3 mt-md-0 small-mt-0">{{__('Set Priority')}}</h4>
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
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Pricing Rules")}}</button>

                            <!--<a href="{{ route('pricing-rules.create') }}"
                                class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>
                                Add Pricing Rules</a> -->
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100" id="pricing-datatable">
                            <thead>
                                <tr>
                                    <th>{{__("Name")}}</th>
                                    <th>{{__("Base Price")}}</th>
                                    <th>{{__("Base Duration")}}</th>
                                    <th>{{__("Base Distance")}}</th>
                                   {{--<th>{{__("Base Waiting")}}</th>--}}
                                    <th style="width: 85px;">{{__("Action")}}</th>
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
                                       {{-- <td>
                                            {{ $price->base_waiting }}
                                        </td>--}}
                                        

                                        <td>
                                            @if($price->is_default == 0 || Auth::user()->is_superadmin == 1)
                                                <div class="form-ul" style="width: 60px;">
                                                <div class="inner-div"> <a href="#" href1="{{ route('pricing-rules.edit', $price->id) }}" class="action-icon editIcon" priceId="{{$price->id}}"> <i class="mdi mdi-square-edit-outline"></i></a> 
                                                </div>
                                            @endif    
                                                @if($price->is_default == 0)
                                                <div class="inner-div">
                                                    <form method="POST" action="{{route('pricing-rules.destroy', $price->id)}}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary-outline action-icon" onclick="return confirm('{{__('Are you sure?')}}');"> <i class="mdi mdi-delete"></i></button>
    
                                                        </div>
                                                    </form>
                                                    </div>
                                                @endif
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

<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script src="{{ asset('assets/js/storeAgent.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/9.0.10/js/intlTelInput.js"></script>

<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>


<script>

    $(document).on('click', '.selectpicker', function( event ) {
        event.stopPropagation();
    }); 


    $(document).ready(function() {
        const date = new Date();
        date.toLocaleTimeString('en-US', {timeZone: "{{$timezone}}"});

        clockUpdate();
        setInterval(clockUpdate, 1000);

        $('#pricing-datatable').DataTable({
            language: {
                    search: "",
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    },
                    searchPlaceholder: "{{__('Search')}}",
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"></div>'
                },
            });
    });

    function clockUpdate() {
        var date = new Date();
        $('.digital-clock1').css({'text-shadow': '0 0 6px #ff0'});

        function addZero(x) {
            if (x < 10) {
            return x = '0' + x;
            } else {
            return x;
            }
        }

        function twelveHour(x) {
            if (x > 12) {
            return x = x - 12;
            } else if (x == 0) {
            return x = 12;
            } else {
            return x;
            }
        }

        function twentyfourHour(x) {
            if (x > 24) {
            return x = x - 24;
            } else if (x == 0) {
            return x = 24;
            } else {
            return x;
            }
        }

        var h = addZero(twelveHour(date.getHours()));
        var m = addZero(date.getMinutes());
        var s = addZero(date.getSeconds());

        $('.digital-clock1').text("Current Time: "+h + ':' + m + ':' + s)
        }

    function runPicker(){
        $('.datetime-datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });

        $('.selectpicker').selectpicker();
        // timepicker for days and time
        $("[id^='price_starttime_'], [id^='price_endtime_']").flatpickr({enableTime:!0,noCalendar:!0,dateFormat:"H:i",time_24hr:!0});
    }

    $('.openModal').click(function(){
        $('#add-pricing-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        runPicker();
    });

    // click event of add new time frame button
    $(document).on('click', '.add_sub_pricing_row', function(){
        var rowid = $(this).attr("data-id");
        var id = $("#no_of_time_"+rowid).val();
        if($("#price_starttime_"+rowid+"_"+id).val()!='' && $("#price_endtime_"+rowid+"_"+id).val()!='')
        {
            id = parseInt(id) + 1;
            //new time frame row creation under day row
            $("#timeframe_tbody_"+rowid).append('<tr id="timeframe_row_'+rowid+'_'+id+'"><td></td><td><input id="price_starttime_'+rowid+'_'+id+'" class="form-control" autocomplete="off" placeholder="00:00" name="price_starttime_'+rowid+'_'+id+'" type="text" readonly="readonly"></td><td><input id="price_endtime_'+rowid+'_'+id+'" class="form-control" autocomplete="off" placeholder="00:00" name="price_endtime_'+rowid+'_'+id+'" type="text" readonly="readonly"></td><td style="text-align:center;"><span data-id="pricruledelspan_'+rowid+'_'+id+'" class="del_pricrule_span"><img style="filter: grayscale(.5);" src="{{asset("assets/images/ic_delete.png")}}"  alt=""></span></td></tr>');
            $("#no_of_time_"+rowid).val(id);
            $("#price_starttime_"+rowid+"_"+id+", #price_endtime_"+rowid+"_"+id).flatpickr({enableTime:!0,noCalendar:!0,dateFormat:"H:i",time_24hr:!0});
        }else{
            //empty previous row fields warning
            var color = 'orange';var heading="Warning!";
            $.toast({ 
            heading:heading,
            text : "Please fill previous data first.", 
            showHideTransition : 'slide', 
            bgColor : color,              
            textColor : '#eee',            
            allowToastClose : true,      
            hideAfter : 5000,            
            stack : 5,                   
            textAlign : 'left',         
            position : 'top-right'      
            });
            //focus empty field
            if($("#price_starttime_"+rowid+"_"+id).val()=='')
            {
                $("#price_starttime_"+rowid+"_"+id).focus();
            }else{
                $("#price_endtime_"+rowid+"_"+id).focus();
            }
            
        }
    });

    $(document).on('click', '.del_pricrule_span', function(){
        //var rowid = $(this).attr("data-id");
        //var id_arr = rowid.split('_');
        Swal.fire({
                title: "Are you sure?",
                text:"You want to delete this row ?.",
                showCancelButton: true,
                confirmButtonText: 'Ok',
            }).then((result) => {
                if(result.value)
                {
                    $(this).closest("tr").remove();
                }
                
            });
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
                //$('.custom-switch').switch();

            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.submitEditForm', function(){ console.log('ad');
        document.getElementById("edit_price").submit();
    });

</script>

@endsection
