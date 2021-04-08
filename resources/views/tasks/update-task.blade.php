@extends('layouts.vertical', ['title' => 'Tasks'])
@section('css')

@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Task</h4>
                </div>
            </div>
        </div>
        <!-- start page title -->

        <!-- end page title -->
        {!! Form::model($task, ['route' => ['tasks.update', $task->id], 'enctype' => 'multipart/form-data']) !!}
        {{ method_field('PATCH') }}
        @csrf
        <div class="row">

            <div class="col-md-6">
                <div class="card-box">
                    @csrf
                    <div class="row d-flex align-items-center" id="dateredio">
                        <div class="col-md-3">
                            <h4 class="header-title mb-3">Customer</h4>
                        </div>
                        <div class="col-md-5 text-right">
                            <div class="login-form">
                                <ul class="list-inline">
                                    <li class="d-inline-block mr-2">
                                        <input type="radio" class="custom-control-input check" id="tasknow" name="task_type"
                                            value="now" checked>
                                        <label class="custom-control-label" for="tasknow">Now</label>
                                    </li>
                                    <li class="d-inline-block">
                                        <input type="radio" class="custom-control-input check" id="taskschedule"
                                            name="task_type" value="schedule">
                                        <label class="custom-control-label" for="taskschedule">Schedule</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 datenow">
                            <input type="text" id='datetime-datepicker' name="schedule_time" class="form-control upside"
                                placeholder="Date Time">
                        </div>

                    </div>

                    <span class="span1 searchspan">Please search a customer or add a customer</span>
                    <div class="row searchshow">
                        <div class="col-md-8">
                            <div class="form-group" id="nameInput">

                                <input type="text" id='search' class="form-control" name="search"
                                    placeholder="search Customer" value="{{ $task->customer->name }}">
                                <input type="hidden" id='cusid' name="ids" value="{{ $task->customer->id }}" readonly>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="AddressInput">
                                <a href="#" class="add-sub-task-btn">New Customer</a>

                            </div>
                        </div>

                    </div>

                    <div class="newcus shows">
                        <div class="row ">
                            <div class="col-md-3">
                                <div class="form-group" id="">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="">
                                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="">
                                    {!! Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => 'Phone
                                    Number']) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" id="Inputsearch">
                                    <a href="#" class="add-sub-task-btn">Previous</a>

                                </div>

                            </div>
                        </div>
                    </div>
                    @php
                        $newcount = 0;
                    @endphp
                    <div class="taskrepet" id="newadd">
                        @foreach ($task->task as $keys => $item)
                            @php
                                $maincount = 0;
                                $newcount++;
                            @endphp
                            <div class="copyin{{ $keys == 0 ? '1' : '' }}" id="copyin1">
                                <div class="requried allset">
                                    <div class="row firstclone1">

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <select class="form-control selecttype mt-1 taskselect" id="task_type"
                                                    name="task_type_id[]" required>
                                                    <option value="1" {{ $item->task_type_id == 1 ? 'selected' : '' }}>
                                                        Pickup Task</option>
                                                    <option value="2" {{ $item->task_type_id == 2 ? 'selected' : '' }}>Drop
                                                        Off Task</option>
                                                    <option value="3" {{ $item->task_type_id == 3 ? 'selected' : '' }}>
                                                        Appointment</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group {{ $item->task_type_id == 3 ? 'newclass' : 'appoint' }}">
                                                <input type="text" class="form-control appointment_date"
                                                    name="appointment_date[]" placeholder="Duration (In Min)"
                                                    value="{{ $item->allocation_type }}">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>


                                        </div>
                                        <div class="col-md-1 ">

                                            <span class="span1 onedelete" id="spancheck"><img style="filter: grayscale(.5);"
                                                    src="{{ asset('assets/images/ic_delete.png') }}" alt=""></span>


                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 class="header-title mb-2">Address</h4>
                                        </div>
                                        <div class="col-md-6">
                                            {{-- <h4 class="header-title mb-2">Saved Addresses</h4> --}}
                                        </div>
                                    </div>
                                    <span class="span1 addspan">Please select a address or create new</span>

                                    <div class="row">
                                        <div class="col-md-6 cust1_add_div" id="add{{ $newcount }}">
                                            <div class="form-group alladdress" id="typeInput">
                                                {!! Form::text('short_name[]', null, ['class' => 'form-control address',
                                                'placeholder' => 'Address Short Name']) !!}

                                                <div class="form-group input-group" id="addressInput">
                                                    <input type="text" id="add{{ $newcount }}-input" name="address[]"
                                                        class="form-control cust1_add" placeholder="Address">
                                                    <div class="input-group-append">
                                                        <button
                                                            class="btn btn-xs btn-dark waves-effect waves-light showMapTask cust1_btn"
                                                            type="button" num="add{{ $newcount }}"> <i
                                                                class="mdi mdi-map-marker-radius"></i></button>
                                                    </div>
                                                    <input type="hidden" name="latitude[]"
                                                        id="add{{ $newcount }}-latitude" class="cust1_latitude"
                                                        value="0" />
                                                    <input type="hidden" name="longitude[]"
                                                        id="add{{ $newcount }}-longitude" class="cust1_longitude"
                                                        value="0" />
                                                    <span class="invalid-feedback" role="alert" id="address">
                                                        <strong></strong>
                                                    </span>
                                                </div>

                                                <input type="text" name="post_code[]"
                                                id="add{{ $newcount }}-postcode" class="form-control address postcode"
                                                placeholder="PostsCode" />

                                                <div class="row no-gutters">
                                                    <div class="col-6 pr-1">
                                                        {!! Form::text('barcode[]', null, ['class' => 'form-control barcode','placeholder' => 'Task Barcode']) !!}  
                                                    </div>
                                                    <div class="col-6 pl-1">
                                                        {!! Form::text('quantity[]', null, ['class' => 'form-control quantity','placeholder' => 'Quantity']) !!}
                                                    </div>
                                                </div> 

                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group withradio" id="typeInputss">

                                                <div class="oldhide">

                                                    <img class="showsimage"
                                                        src="{{ url('assets/images/ic_location_placeholder.png') }}"
                                                        alt="">
                                                </div>
                                                @foreach ($task->customer->location as $key => $items)

                                                    <div class="append">
                                                        <div class="custom-control custom-radio"><input type="radio"
                                                                id="{{ $keys }}{{ $items->id }}{{ 12 }}"
                                                                name="old_address_id{{ $keys != 0 ? $keys : '' }}"
                                                                value="{{ $items->id }}"
                                                                {{ $item->location_id == $items->id ? 'checked' : '' }}
                                                                class="custom-control-input redio"><label
                                                                class="custom-control-label"
                                                                for="{{ $keys }}{{ $items->id }}{{ 12 }}"><span
                                                                    class="spanbold">{{ $items->short_name }}</span>-{{ $items->address }}</label>
                                                        </div>
                                                    </div>

                                                @endforeach
                                                @php $maincount++; @endphp
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="new3">
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="row">
                        <div class="col-md-12" id="adds">
                            <a href="#" class="add-sub-task-btn waves-effect waves-light subTask">Add Sub
                                Task</a>
                        </div>
                    </div>

                    <!-- end row -->

                    <!-- container -->
                    <h4 class="header-title mb-2">Meta Data</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="make_modelInput">
                                {!! Form::text('recipient_phone', null, ['class' => 'form-control rec', 'placeholder' =>
                                'Recipient Phone']) !!}
                                {!! Form::email('Recipient_email', null, ['class' => 'form-control rec', 'placeholder' =>
                                'Recipient Email']) !!}
                                {!! Form::textarea('task_description', null, ['class' => 'form-control', 'placeholder' =>
                                'Task Description', 'rows' => 2, 'cols' => 40]) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="colorInput">
                                <label class="btn btn-info width-lg waves-effect waves-light newchnageimage upload-img-btn">
                                    <span><i class="fas fa-image mr-2"></i>Upload Image</span>
                                    <input id="file" type="file" name="file[]" multiple style="display: none" />
                                </label>
                                {{-- @php
                                        dd($images);
                                    @endphp --}}
                                @if ($images[0] == '')
                                    <img class="showsimagegall" src="{{ url('assets/images/ic_image_placeholder.png') }}"
                                        alt="">
                                @endif

                                @if (count($images) > 0 && $images[0] != '')
                                    <div class="allimages">
                                        <div id="imagePreview" class="privewcheck">
                                            @foreach ($images as $item)

                                                <img src="{{ $main }}{{ $item }}" class="imagepri" />
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>



                    <h4 class="header-title mb-3">Allocation</h4>
                    <div class="row my-3" id="rediodiv">
                        <div class="col-md-8">
                            <div class="login-form">
                                <ul class="list-inline">
                                    <li class="d-inline-block mr-2">
                                        <input type="radio" class="custom-control-input check assignRadio" id="customRadio"
                                            name="allocation_type" value="u"
                                            {{ $task->auto_alloction == 'u' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customRadio">Unassigned</label>
                                    </li>
                                    <li class="d-inline-block mr-2">
                                        <input type="radio" class="custom-control-input check assignRadio"
                                            id="customRadio22" name="allocation_type" value="a"
                                            {{ $task->auto_alloction == 'a' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customRadio22">Auto Allocation</label>
                                    </li>
                                    <li class="d-inline-block">
                                        <input type="radio" class="custom-control-input check assignRadio"
                                            id="customRadio33" name="allocation_type" value="m"
                                            {{ $task->auto_alloction == 'm' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customRadio33">Manual</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" placeholder="Cash to be collected"
                                name="cash_to_be_collected"
                                value="{{ isset($task->cash_to_be_collected) ? $task->cash_to_be_collected : '' }}">
                        </div>
                    </div>
                    <span class="span1 tagspan">Please select atlest one tag for driver and agent</span>
                    <div class="tags">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Team Tag</label>
                                    <select name="team_tag[]" id="selectize-optgroups" multiple placeholder="Select tag...">
                                        <option value="">Select Tag...</option>
                                        @foreach ($teamTag as $item)
                                            <option value="{{ $item->id }}"
                                                {{ in_array($item->id, $saveteamtag) ? 'selected' : '' }}>{{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Driver Tag</label>
                                    <select name="agent_tag[]" id="selectize-optgroup" multiple placeholder="Select tag...">
                                        <option value="">Select Tag...</option>
                                        @foreach ($agentTag as $item)
                                            <option value="{{ $item->id }}"
                                                {{ in_array($item->id, $savedrivertag) ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row drivers">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Drivers</label>
                                <select class="form-control" name="agent" id="location_accuracy">
                                    @foreach ($agents as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $task->driver_id == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            <button type="submit"
                                class="btn btn-block btn-lg btn-blue waves-effect waves-light">Submit</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {!! Form::close() !!}




    </div>

    <div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Select Location</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body p-4">

                    <div class="row">
                        <form id="task_form" action="#" method="POST" style="width: 100%">
                            <div class="col-md-12">
                                <div id="googleMap" style="height: 500px; min-width: 500px; width:100%"></div>
                                <input type="hidden" name="lat_input" id="lat_map" value="0" />
                                <input type="hidden" name="lng_input" id="lng_map" value="0" />
                                <input type="hidden" name="for" id="map_for" value="" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light selectMapLocation">Ok</button>
                    <!--<button type="Cancel" class="btn btn-blue waves-effect waves-light cancelMapLocation">cancel</button>-->
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced2.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    @include('tasks.updatepagescript')
    
    
    
@endsection
