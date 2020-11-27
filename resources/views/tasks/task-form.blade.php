@csrf
<div class="row">

    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3">Customer</h4>
            @csrf
            <span class="span1 searchspan">Please search a customer or add a customer</span>
            <div class="row searchshow">
                <div class="col-md-8">
                    <div class="form-group" id="nameInput">

                        {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'search Customer',
                        'id' => 'search']) !!}
                        <input type="hidden" id='cusid' name="ids" readonly>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="AddressInput">
                        <a href="#" class=" form-control btn btn-blue waves-effect waves-light newAdd"><i
                                class="mdi mdi-plus-circle mr-1"></i>New Customer</a>

                    </div>
                </div>

            </div>

            <div class="row newcus shows">
                <div class="col-md-3">
                    <div class="form-group" id="">
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="form-group" id="Inputsearch">
                        <a href="#" class=" form-control btn btn-blue waves-effect waves-light">Previous Customer</a>

                    </div>

                </div>
            </div>
            <h4 class="header-title mb-3">Task</h4>
            <div class="taskrepet">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <select class="form-control" id="task_type" name="task_type_id">

                                <option value="1">Pickup</option>
                                <option value="2">Drop</option>
                                <option value="3">Appintment</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="make_modelInput">
                            {!! Form::text('appointment_date', null, ['class' => 'form-control appointment_date',
                            'placeholder' => 'Appointment Date', 'id' => 'datetime-datepicker']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>


                    </div>
                </div>
                <h4 lass="header-title mb-3">Address</h4>
                <span class="span1 addspan">Please select a address or create new</span>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" id="typeInput">
                            {!! Form::text('short_name', null, ['class' => 'form-control address', 'placeholder' =>
                            'Address Short Name']) !!}
                            {!! Form::textarea('address', null, ['class' => 'form-control address', 'placeholder' =>
                            'Full Address', 'rows' => 2]) !!}
                            {!! Form::text('post_code', null, ['class' => 'form-control address', 'placeholder' => 'Post
                            Code']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="typeInputss">
                            <h5 class="oldhide">Select Customer For Saved Address</h5>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">

                </div>
                <div class="col-md-7" id="adds">
                    <a href="#" class="btn btn-success btn-rounded waves-effect waves-light">Add Task</a>
                </div>
            </div>
            <h4 class="header-title mb-3">Meta Data</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::text('recipient_phone', null, ['class' => 'form-control rec', 'placeholder' =>
                        'Recipient Phone', 'required' => 'required']) !!}
                        {!! Form::email('recipient_email', null, ['class' => 'form-control rec', 'placeholder' =>
                        'Recipient Email', 'required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                        <div class="form-group" id="make_modelInput">
                            {!! Form::textarea('task_description', null, ['class' => 'form-control', 'placeholder' =>
                            'Task_description', 'rows' => 3, 'cols' => 40]) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title"></h4>
                           
    
                            <form action="/" method="post" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews"
                                data-upload-preview-template="#uploadPreviewTemplate">
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
    
                                <div class="dz-message needsclick">
                                    <i class="h1 text-muted dripicons-cloud-upload"></i>
                                    <h3>Drop files here or click to upload.</h3>
                                    <span class="text-muted font-13"></span>
                                </div>
                            </form>
    
                            <!-- Preview -->
                            <div class="dropzone-previews mt-3" id="file-previews"></div>  
    
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div><!-- end col -->
            </div>
            <!-- end row -->  
    
            <!-- file preview template -->
            <div class="d-none" id="uploadPreviewTemplate">
                <div class="card mt-1 mb-0 shadow-none border">
                    <div class="p-2">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                            </div>
                            <div class="col pl-0">
                                <a href="javascript:void(0);" class="text-muted font-weight-bold" data-dz-name></a>
                                <p class="mb-0" data-dz-size></p>
                            </div>
                            <div class="col-auto">
                                <!-- Button -->
                                <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                    <i class="dripicons-cross"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="header-title mb-3">Allocation</h4>
            <div class="row my-3" id="rediodiv">
                <div class="col-md-4 padd">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input check" id="customRadio" name="allocation_type"
                            value="Un-Assigend" checked>
                        <label class="custom-control-label" for="customRadio">Un-Assigend</label>
                    </div>
                </div>
                <div class="col-md-4 padd">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input check" id="customRadio22" name="allocation_type"
                            value="auto">
                        <label class="custom-control-label" for="customRadio22">Auto Alloc</label>
                    </div>
                </div>
                <div class="col-md-4 padd">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input check" id="customRadio33" name="allocation_type"
                            value="Manual">
                        <label class="custom-control-label" for="customRadio33">Manual</label>
                    </div>
                </div>
            </div>
            <span class="span1 tagspan">Please select atlest one tag for driver and agent</span>
            <div class="row tags">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Team Tag</label>
                        <select name="team_tag[]" id="selectize-optgroups" multiple placeholder="Select tag...">
                            <option value="">Select Tag...</option>
                            @foreach ($teamTag as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row drivers">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label>Drivers</label>
                        <select class="form-control" name="agent" id="driverselect">
                            @foreach ($agents as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5">

                </div>
                <div class="col-md-7">
                    <button type="submit" class="btn btn-blue waves-effect waves-light ">Submit</button>
                </div>
            </div>



        </div>
    </div>


</div>
