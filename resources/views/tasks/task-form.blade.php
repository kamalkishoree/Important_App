@csrf
<div class="row">

    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3">Customer</h4>
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group" id="nameInput">
                        {!! Form::label('title', 'Search',['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control','placeholder'=> 'search Customer']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="AddressInput">
                    <a href="#" class=" form-control btn btn-blue waves-effect waves-light newAdd"><i class="mdi mdi-plus-circle mr-1" ></i>Add Customer</a>

                    </div>
                </div>

            </div>

            <div class="row newcus shows">
                 <div class="col-md-4">
                    <div class="form-group" id="">
                        {!! Form::text('name', null, ['class' => 'form-control','placeholder'=> 'Name']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="">
                        {!! Form::text('email', null, ['class' => 'form-control','placeholder'=> 'Email']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="">
                        {!! Form::text('phone_number', null, ['class' => 'form-control','placeholder'=> 'Phone Number']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
            </div>
            <h4 class="header-title mb-3">Task</h4>
            <div class="row">
                 <div class="col-md-6">
                    <div class="form-group mb-3">
                        <select class="form-control" id="task_type" name="location_accuracy">
                           
                                <option value="1">Pickup</option>
                                <option value="2">Drop</option>
                                <option value="3">Appintment</option>
                           
                        </select>
                    </div>
                 </div>   
                <div class="col-md-6">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::text('appointment_date', null, ['class' => 'form-control appointment_date','placeholder'=> 'Appointment Date','id'=>'datetime-datepicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                    {!! Form::text('short_name', null, ['class' => 'form-control address','placeholder'=> 'Address Short Name',]) !!}
                    {!! Form::textarea('address', null, ['class' => 'form-control address','placeholder'=> 'Full Address','rows'=>2,]) !!}
                    {!! Form::text('post_code', null, ['class' => 'form-control address','placeholder'=> 'Post Code',]) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                    <label for="title" class="control-label">Home</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio1">Phase 8 mohali punjab,14006</label>
                                    </div>
                                    <label for="title" class="control-label mt-2">Office</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio2">Phase 10 mohali punjab,14006</label>
                                    </div>
                                </div>
                    
                </div>
            </div>
            <h4 class="header-title mb-3">Meta Data</h4>
            <div class="row">
                <div class="col-md-6">
                <div class="form-group" id="make_modelInput">
                        {!! Form::text('recipient_phone', null, ['class' => 'form-control rec','placeholder'=> 'Recipient Phone']) !!}
                        {!! Form::email('recipient_phone', null, ['class' => 'form-control rec','placeholder'=> 'Recipient Email']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                    <div class="form-group" id="make_modelInput">
                        {!! Form::textarea('task_description', null, ['class' => 'form-control','placeholder'=> 'Task_description','rows' => 3, 'cols' => 40]) !!}
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
                        <form action="/" method="post" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews"
                            data-upload-preview-template="#uploadPreviewTemplate">
                            <div class="fallback">
                                <input name="file" type="file" multiple />
                            </div>

                            <div class="dz-message needsclick">
                                <i class="h1 text-muted dripicons-cloud-upload newchnage"></i>
                                <h3>Drop Task Images here</h3>
                            </div>
                        </form>

                        <!-- Preview -->
                        <div class="dropzone-previews mt-3" id="file-previews"></div>  

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div><!-- end col -->
        </div>
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
            <input type="radio" class="custom-control-input check" id="customRadio" name="example" value="Un-Assigend">
            <label class="custom-control-label" for="customRadio">Un-Assigend</label>
            </div>
        </div>  
        <div class="col-md-4 padd">  
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input check" id="customRadio22" name="example" value="auto">
                <label class="custom-control-label" for="customRadio22">Auto Alloc</label>
            </div>
        </div>
        <div class="col-md-4 padd">    
            <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input check" id="customRadio33" name="example" value="Manual">
            <label class="custom-control-label" for="customRadio33">Manual</label>
            </div>
        </div>
        </div>
        <div class="row tags">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Driver Tag</label>
                    <select id="selectize-optgroup" multiple placeholder="Select tag...">
                    <option value="">Select Tag...</option>
                    <optgroup label="Climbing">
                    <option value="pitons">Best</option>
                    <option value="cams">Cams</option>
                    <option value="nuts">Nuts</option>
                    <option value="bolts">Bolts</option>
                    <option value="stoppers">Stoppers</option>
                    <option value="sling">Sling</option>
                    </optgroup>
        
                    </select>
                </div>
            </div>
        <div class="col-md-6">
           <div class="form-group mb-3">
                <label>Team Tag</label>
                <select  name="team_tag" id="selectize-optgroups" multiple placeholder="Select tag...">
                    <option value="">Select Tag...</option>
                    <optgroup label="Climbing">
                    <option value="pitons">Best</option>
                    <option value="cams">Cams</option>
                    <option value="nuts">Nuts</option>
                    <option value="bolts">Bolts</option>
                    <option value="stoppers">Stoppers</option>
                    <option value="sling">Sling</option>
                    </optgroup>

                </select>
            </div>
        </div>
        
    </div>

    <div class="row drivers">
        <div class="col-md-12">
           <div class="form-group mb-3">
            <label>Drivers</label>
               <select class="form-control" id="location_accuracy" name="location_accuracy">
                  
                       <option value="">New Driver</option>
                       <option value="">Driver One</option>
                       <option value="">My Driver</option>
                  
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

