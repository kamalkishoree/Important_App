<div class="row">
    <div class="col-md-12">
        <h4 class="page-title">Add Task</h4>
        <div class="card-box">
            @csrf
            <div class="row d-flex align-items-center" id="dateredio">
                
                <div class="col-sm-2">
                    <h4 class="header-title mb-3">Customer</h4>
                </div>
                <div class="col-sm-5 text-right">
                    <div class="login-form">
                        <ul class="list-inline">
                            <li class="d-inline-block mr-2">
                                <input type="radio" class="custom-control-input check" id="tasknow"
                                name="task_type" value="now" checked>
                                <label class="custom-control-label" for="tasknow">Now</label>
                            </li>
                            <li class="d-inline-block">
                                <input type="radio" class="custom-control-input check" id="taskschedule"
                                name="task_type" value="schedule" >
                                <label class="custom-control-label" for="taskschedule">Schedule</label>
                            </li>
                          </ul>
                        </div>
                </div>
                <div class="col-sm-5 datenow">
                    <input type="text"  name="schedule_time"
                        class="form-control opendatepicker upside datetime-datepicker" placeholder="Date Time">
                        <button type="button" class="cstmbtn check_btn btn btn-info"><i class="fa fa-check" aria-hidden="true"></i></button>
                </div>
            </div>

            <span class="span1 searchspan">Please search a customer or add a customer</span>
            <div class="row searchshow">
                <div class="col-md-8">
                    <div class="form-group" id="nameInputHeader">

                        {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Search Customer', 'id' => 'searchCust']) !!}
                        <input type="hidden" id='cusid' name="ids" readonly>

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
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name','id'=>'name_new']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="">
                            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email','id'=>'email_new']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" id="">
                            {!! Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => 'Phone Number','id'=> 'phone_new'
                            ]) !!}
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
            

            <div class="taskrepet newAddHead" id="newadd">
                <div class="copyin1 cloningDiv" id="copyin1">
                  <div class="requried allset">
                    <div class="row firstclone1">
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <select class="form-control selecttype mt-1 taskselect" id="task_type"  name="task_type_id[]" required>
                                    <option value="1">Pickup Task</option>
                                    <option value="2">Drop Off Task</option>
                                    <option value="3">Appointment</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group appoint mt-1">
                                {!! Form::text('appointment_date[]', null, ['class' => 'form-control
                                appointment_date', 'placeholder' => 'Duration (In Min)']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>


                        </div>
                        <div class="col-md-1 text-center pt-2" >
                            <span class="span1 delbtnhead" id="spancheck"><img style="filter: grayscale(.5);" src="{{asset('assets/images/ic_delete.png')}}"  alt=""></span>
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

                    <div class="row cust_add_div" id="addHeader1">
                        <div class="col-md-6">
                            <div class="form-group alladdress" id="typeInput">
                                {!! Form::text('short_name[]', null, ['class' => 'form-control address',
                                'placeholder' => 'Short Name']) !!}
                                <div class="input-group mb-1">
                                    <input type="text" id="addHeader1-input" name="address[]" class="form-control address cust_add" placeholder="Location">
                                    <div class="input-group-append">
                                        <button class="btn btn-xs btn-dark waves-effect waves-light showMapHeader cust_btn" type="button" num="addHeader1"> <i class="mdi mdi-map-marker-radius"></i></button>
                                    </div>
                                </div>
                                <input type="hidden" name="latitude[]" id="addHeader1-latitude" value="0" class="cust_latitude" />
                                <input type="hidden" name="longitude[]" id="addHeader1-longitude" value="0" class="cust_longitude" />
                                {!! Form::text('post_code[]', null, ['class' => 'form-control address postcode','placeholder' => 'Post Code','id'=>'addHeader1-postcode']) !!}
                                
                                {!! Form::text('address_email[]', null, ['class' => 'form-control address address_email','placeholder' => 'Email','id'=>'addHeader1-address_email']) !!}
                                {!! Form::text('address_phone_number[]', null, ['class' => 'form-control address address_phone_number','placeholder' => 'Phone Number','id'=>'addHeader1-address_phone_number']) !!}
                                    
                                 <div class="row no-gutters">
                                    <div class="col-6 pr-1">
                                        {!! Form::text('barcode[]', null, ['class' => 'form-control barcode','placeholder' => 'Task Barcode']) !!}  
                                    </div>
                                    <div class="col-6 pl-1">
                                        {!! Form::text('quantity[]', null, ['class' => 'form-control quantity onlynumber','placeholder' => 'Quantity']) !!}
                                    </div>
                                 </div>   

                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6" id="onlyFirst">
                            <div class="form-group withradio" id="typeInputss">
                                <div class="oldhide text-center">
                                    <img class="showsimage" src="{{url('assets/images/ic_location_placeholder.png')}}" alt="">
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                    
                  </div>
                </div>
                <div id="addSubFields" style="width:100%;height:400px; display: none;">&nbsp;</div>
              </div>
            <div class="row">
                <div class="col-md-12" id="adds">
                    <a href="#" class="add-sub-task-btn waves-effect waves-light subTaskHeader">Add Sub
                        Task</a>
                </div>
            </div>

            <!-- end row -->

            <!-- container -->
            <h4 class="header-title mb-2">Meta Data</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::hidden('recipient_phone', null, ['class' => 'form-control rec', 'placeholder' =>
                        'Recipient Phone', 'required' => 'required']) !!}
                        {!! Form::hidden('recipient_email', null, ['class' => 'form-control rec', 'placeholder'
                        => 'Recipient Email', 'required' => 'required']) !!}
                            {!! Form::textarea('task_description', null, ['class' => 'form-control',
                            'placeholder' => 'Task Description', 'rows' => 2, 'cols' => 40]) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                       
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                   
                    <div class="form-group text-center" id="colorInput">
                        <label class="btn btn-info width-lg waves-effect waves-light newchnageimage upload-img-btn">
                            <span><i class="fas fa-image mr-2"></i>Upload Image</span>
                            <input id="file" type="file" name="file[]" multiple style="display: none"/>
                        </label>
                        <div>
                            <img class="showsimagegall" src="{{url('assets/images/ic_image_placeholder.png')}}" alt="">
                        </div>
                        <div class="allimages">
                          <div id="imagePreview" class="privewcheck"></div>
                        </div>
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
                                name="allocation_type" value="u" {{$allcation->manual_allocation == 0 ?'checked':''}}>
                            <label class="custom-control-label" for="customRadio">Unassigned</label>
                            </li>
                            <li class="d-inline-block mr-2">
                                <input type="radio" class="custom-control-input check assignRadio" id="customRadio22"
                                name="allocation_type" value="a" {{$allcation->manual_allocation == 1 ?'checked':''}}>
                            <label class="custom-control-label" for="customRadio22">Auto Allocation</label>
                            </li>
                            <li class="d-inline-block">
                                <input type="radio" class="custom-control-input check assignRadio" id="customRadio33"
                                name="allocation_type" value="m">
                            <label class="custom-control-label" for="customRadio33">Manual</label>
                            </li>
                         </ul>
                        </div>
                </div>
                <div class="col-md-4">
                    <input class="form-control" type="text" placeholder="Cash to be collected" name="cash_to_be_collected">
                </div>
              
            </div>
            <span class="span1 tagspan">Please select atlest one tag for driver and agent</span>
            <div class="tags {{ $allcation->manual_allocation == 0 ? "hidealloction":""}}">
                <div class="row ">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Team Tag</label>
                            <select name="team_tag[]" id="selectize-optgroups" class="selectizeInput" multiple placeholder="Select tag...">
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
                            <select name="agent_tag[]" id="selectize-optgroup" class="selectizeInput" multiple placeholder="Select tag...">
                                <option value="">Select Tag...</option>
                                @foreach ($agentTag as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row drivers hidealloction">
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
        </div>
    </div>
</div>