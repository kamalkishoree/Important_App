@csrf
<div class="row">

    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3">Add Task</h4>
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="nameInput">
                        {{-- {!! Form::label('title', 'Search Customer',['class' => 'control-label',]) !!} --}}
                        {!! Form::text('name', null, ['class' => 'form-control','placeholder'=> 'Type here for search']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="nameInput">
                       <button class="btn btn-blue waves-effect waves-light addnew " >Add Customer</button>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group" id="make_modelInput">
                        {{-- {!! Form::label('title', 'Name',['class' => 'control-label']) !!} --}}
                        {!! Form::text('name', null, ['class' => 'form-control','placeholder'=> 'Name Here']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="make_modelInput">
                        {{-- {!! Form::label('title', 'Email',['class' => 'control-label']) !!} --}}
                        {!! Form::email('email', null, ['class' => 'form-control','placeholder'=> 'Email']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="make_modelInput">
                        {{-- {!! Form::label('title', 'Phone_no',['class' => 'control-label']) !!} --}}
                        {!! Form::text('from_address', null, ['class' => 'form-control','placeholder'=> 'Phone Number']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <h4>Address Goes here</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        <label for="type" class="control-label">Task Type</label>
                        <select class="selectpicker" data-style="btn-light" name="priority" id="type">
                            <option value="low">Pickup</option>
                            <option value="normal">Drop</option>
                            <option value="high">Appointment</option>
                        </select>
                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Appointment duration',['class' => 'control-label']) !!}
                            {!! Form::text('expected_delivery_date', null, ['class' => 'form-control']) !!}  
                        </div>
                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Address',['class' => 'control-label']) !!}
                            {!! Form::text('expected_delivery_date', null, ['class' => 'form-control']) !!}  
                        </div>
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">
                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Saved Address',['class' => 'control-label']) !!}
                            {!! Form::textarea('expected_delivery_date', null, ['class' => 'form-control']) !!}  
                        </div>
                    </div>

                </div>
            </div>
            <button type="button" class="btn btn-info waves-effect waves-light" style="margin-left: 48%;margin-bottom:20px;">Add Task</button>
            <h4>Recipent Details Goes here</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4 addnew">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Scheduled Date</label>
                             </div>
                        </div>
                        <div class="col-md-8">
                            
                                {!! Form::label('title', 'Select Data Time',['class' => 'control-label']) !!}
                                {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!} 
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                             
                        </div>
                </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Recipient Phone',['class' => 'control-label']) !!}
                            {!! Form::text('recipient_phone', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}  
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Recipient Email',['class' => 'control-label']) !!}
                            {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}  
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                       
                        {!! Form::label('title', 'Task Description ',['class' => 'control-label']) !!}
                        {!! Form::text('expected_delivery_date', null, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                    

                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="mt-3">
                        <input type="file" data-plugins="dropify" data-default-file="{{asset('assets/images/small/img-2.jpg')}}"  />
                        <p class="text-muted text-center mt-2 mb-0">Default File</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mt-3">
                        <input type="file" data-plugins="dropify" disabled="disabled"  />
                        <p class="text-muted text-center mt-2 mb-0">Disabled the input</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mt-3">
                        <input type="file" data-plugins="dropify" data-max-file-size="1M" />
                        <p class="text-muted text-center mt-2 mb-0">Max File size</p>
                    </div>
                </div>
            </div> <!-- end row -->

            <h4>Allocation Logic Goes here</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="custom-control custom-radio addnew">
                        <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                        <label class="custom-control-label" for="customRadio1">Auto Alloction</label>
                    </div>
                    <div class="custom-control custom-radio addnew">
                        <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                        <label class="custom-control-label" for="customRadio2">UnAssigned</label>
                    </div>
                    <div class="custom-control custom-radio addnew">
                        <input type="radio" id="customRadio3" name="customRadio" class="custom-control-input">
                        <label class="custom-control-label" for="customRadio3">Manual</label>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                       
                        {!! Form::label('title', 'Data Accourding Selected',['class' => 'control-label']) !!}
                        {!! Form::textarea('expected_delivery_date', null, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                    

                </div>
            </div>
            <h4>Others</h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        <label for="type" class="control-label">Pricing Rules</label>
                        <select class="selectpicker" data-style="btn-light" name="priority" id="type">
                            <option value="low">Rule1</option>
                            <option value="normal">Rule2</option>
                            <option value="high">Rule3</option>
                        </select>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        <label for="type" class="control-label">Dependent_task</label>
                        <select class="selectpicker" data-style="btn-light" name="priority" id="type">
                            <option value="low">Rule1</option>
                            <option value="normal">Rule2</option>
                            <option value="high">Rule3</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        
                        {!! Form::label('title', 'Estimated_time',['class' => 'control-label']) !!}
                        {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Distance',['class' => 'control-label']) !!}
                            {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}  
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        <label for="type" class="control-label">Status</label>
                        <select class="selectpicker" data-style="btn-light" name="priority" id="type">
                            <option value="low">Assigning</option>
                            <option value="normal">Un-Assigning</option>
                            <option value="high">Assigning</option>
                        </select>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Assigned_time',['class' => 'control-label']) !!}
                            {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}  
                        </div>
                    </div>

                </div>
            </div>
           
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        
                        {!! Form::label('title', 'Accepted_time',['class' => 'control-label']) !!}
                        {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Declined_time',['class' => 'control-label']) !!}
                            {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}  
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        
                        {!! Form::label('title', 'Started_time',['class' => 'control-label']) !!}
                        {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Reached_time',['class' => 'control-label']) !!}
                            {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}  
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        
                        {!! Form::label('title', 'Cancelled_time',['class' => 'control-label']) !!}
                        {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Completed_time',['class' => 'control-label']) !!}
                            {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}  
                        </div>
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