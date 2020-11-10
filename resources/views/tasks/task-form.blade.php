@csrf
<div class="row">

    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3"></h4>
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
                    <div class="form-group" id="nameInput">
                    <a href="#"  class=" form-control btn btn-blue waves-effect waves-light newAdd"><i class="mdi mdi-plus-circle mr-1"></i>Add Customer</a>

                    </div>
                </div>

            </div>

            <div class="row show">
                 <div class="col-md-4">
                    <div class="form-group" id="nameInput">
                        {!! Form::text('name', null, ['class' => 'form-control','placeholder'=> 'Name']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="nameInput">
                        {!! Form::text('email', null, ['class' => 'form-control','placeholder'=> 'Email']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="nameInput">
                        {!! Form::text('phone_number', null, ['class' => 'form-control','placeholder'=> 'Phone Number']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
            </div>

            <div class="row">
                 <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        {!! Form::select('status',['pickup' => 'Pickup','drop' => 'Drop','appointment'=> 'Appointment'],null,['class' => 'selectpicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::text('to_address', null, ['class' => 'form-control','placeholder'=> 'Appointment Date','id'=>'datetime-datepicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                    {!! Form::textarea('address', null, ['class' => 'form-control','placeholder'=> 'Address','rows' => 6, 'cols' => 40]) !!}
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
                                        <label class="custom-control-label" for="customRadio1">Toggle this custom radio</label>
                                    </div>
                                    <label for="title" class="control-label mt-2">Office</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio2">Or toggle this other custom radio</label>
                                    </div>
                                </div>
                    
                </div>
            </div>

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
                                <i class="h1 text-muted dripicons-cloud-upload"></i>
                                <h3>Drop files here or click to upload.</h3>
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

        <div class="row my-3">
        <div class="col-md-12">
        <div class="custom-control custom-radio custom-control-inline">
         <input type="radio" class="custom-control-input" id="customRadio" name="example" value="customEx">
         <label class="custom-control-label" for="customRadio">Un-Assigend</label>
        </div>
       <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="customRadio22" name="example" value="customEx">
        <label class="custom-control-label" for="customRadio22">Auto Alloc</label>
      </div>
     <div class="custom-control custom-radio custom-control-inline">
      <input type="radio" class="custom-control-input" id="customRadio33" name="example" value="customEx">
      <label class="custom-control-label" for="customRadio33">Manual</label>
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

