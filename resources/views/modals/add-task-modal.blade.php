<div class="row">
    <div class="col-md-12">
        
        <div class="card-box p-0 m-0"> 
            @csrf
            <div class="row d-flex align-items-center" id="dateredio">

                <div class="col-sm-2">
                    <h4 class="header-title mb-3">{{__("Customer")}}</h4>
                </div>
                <div class="col-sm-5 text-right">
                    <div class="login-form">
                        <ul class="list-inline">
                            <li class="d-inline-block mr-2">
                                <input type="radio" class="custom-control-input check" id="tasknow"
                                name="task_type" value="now" checked>
                                <label class="custom-control-label" for="tasknow">{{__("Now")}}</label>
                            </li>
                            <li class="d-inline-block">
                                <input type="radio" class="custom-control-input check" id="taskschedule"
                                name="task_type" value="schedule" >
                                <label class="custom-control-label" for="taskschedule">{{__("Schedule")}}</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-5 datenow">
                    <input type="text"  name="schedule_time"
                        class="form-control opendatepicker upside datetime-datepicker" placeholder={{__("Date Time")}}>
                        <button type="button" class="cstmbtn check_btn btn btn-info"><i class="fa fa-check" aria-hidden="true"></i></button>
                </div>
            </div>

            <span class="span1 searchspan">{{__("Please search a customer or add a customer")}}</span>
            <div class="row searchshow">
                <div class="col-md-8">
                    <div class="form-group" id="nameInputHeader">

                        {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => __('Search Customer'), 'id' => 'searchCust']) !!}
                        <input type="hidden" id='cusid' name="ids" readonly>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="AddressInput">
                        <a href="#" class="add-sub-task-btn">{{__("New Customer")}}</a>
                    </div>
                </div>

            </div>
            <div class="newcus shows">
                <div class="row no-gutters row-spacing">
                    <div class="col-md-3">
                        <div class="form-group" id="">
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Name'),'id'=>'name_new']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="">
                            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Email'),'id'=>'email_new']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" id="">
                            {!! Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => __('Phone Number'),'id'=> 'phone_new'
                            ]) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" id="Inputsearch">
                            <a href="#" class="add-sub-task-btn">{{__("Previous")}}</a>

                        </div>

                    </div>
               </div>
            </div>
            
            <input type="hidden" id="check-pickup-barcode" value="{{ (!empty($task_proofs[0]->barcode_requried) ? $task_proofs[0]->barcode_requried : 0)}}">
            <input type="hidden" id="check-drop-barcode" value="{{ (!empty($task_proofs[1]->barcode_requried) ? $task_proofs[1]->barcode_requried : 0)}}">
            <input type="hidden" id="check-appointment-barcode" value="{{ (!empty($task_proofs[2]->barcode_requried) ? $task_proofs[2]->barcode_requried : 0)}}">
            
            <div class="taskrepet newAddHead" id="newadd">
                <div class="copyin1 cloningDiv check-validation" id="copyin1">
                  <div class="requried allset">
                    <div class="row firstclone1">
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <select class="form-control selecttype mt-1 taskselect" id="task_type"  name="task_type_id[]" required>
                                    <option value="1">{{__("Pickup Task")}}</option>
                                    <option value="2">{{__("Drop Off Task")}}</option>
                                    <option value="3">{{__("Appointment")}}</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group appoint mt-1">
                                {!! Form::text('appointment_date[]', null, ['class' => 'form-control
                                appointment_date', 'placeholder' => __('Duration (In Min)')]) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>


                        </div>
                        <div class="col-md-1 text-center pt-2" >
                            <span class="span1 delbtnhead" id="spancheck"><img style="filter: grayscale(.5);" src="{{asset('assets/images/ic_delete.png')}}"  alt=""></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 d-flex align-items-center">
                            <h4 class="header-title mb-0">{{__("Address")}}</h4>
                            <a href="javascript:void(0);" id="clear-address" class="btn btn-info clear-btn ml-3">{{__("Clear")}}</a>
                        </div>
                        <div class="col-md-6">
                            {{-- <h4 class="header-title mb-2">Saved Addresses</h4> --}}
                        </div>
                    </div>
                    
                    <span class="span1 addspan">{{__("Please select a address or create new")}}</span>

                    <div class="row cust_add_div" id="addHeader1">
                        <div class="col-lg-8">
                            <div class="form-group alladdress" id="typeInput">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::text('short_name[]', null, ['class' => 'form-control address', 'placeholder' => __('Short Name')]) !!}
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::text('address_email[]', null, ['class' => 'form-control address address_email','placeholder' => __('Email'),'id'=>'addHeader1-address_email']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-1">
                                            <input type="text" id="addHeader1-input" name="address[]" class="form-control address cust_add" placeholder={{__("Location")}}>
                                            <div class="input-group-append">
                                                <button class="btn btn-xs btn-dark waves-effect waves-light showMapHeader cust_btn" type="button" num="addHeader1"> <i class="mdi mdi-map-marker-radius"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::text('address_phone_number[]', null, ['class' => 'form-control address address_phone_number','placeholder' => __('Phone Number'),'id'=>'addHeader1-address_phone_number']) !!}
                                    </div>
                                    @if($preference->route_flat_input == 1)
                                    <div class="col-md-6">
                                        {!! Form::text('flat_no[]', null, ['class' => 'form-control address flat_no','placeholder' => __('Flat No'),'id'=>'addHeader1-flat_no']) !!}
                                    </div>
                                    @endif
                                    <div class="col-md-6">
                                        <input type="hidden" name="latitude[]" id="addHeader1-latitude" value="0" class="cust_latitude" />
                                        <input type="hidden" name="longitude[]" id="addHeader1-longitude" value="0" class="cust_longitude" />
                                        {!! Form::text('post_code[]', null, ['class' => 'form-control address postcode','placeholder' => __('Post Code'),'id'=>'addHeader1-postcode']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row no-gutters">
                                        
                                            <div class="col-6 pr-1">
                                                {!! Form::text('barcode[]', null, ['class' => 'form-control barcode','placeholder' => __('Task Barcode')]) !!}  
                                            </div>
                                            <div class="col-6 pl-1">
                                                {!! Form::text('quantity[]', null, ['class' => 'form-control quantity onlynumber','placeholder' => __('Quantity')]) !!}
                                            </div>
                                            <span class="span1 pickup-barcode-error">{{__("Task Barcode is required for pickup")}}</span>
                                            <span class="span1 drop-barcode-error">{{__("Task Barcode is required for drop")}}</span>
                                            <span class="span1 appointment-barcode-error">{{ __("Task Barcode is required for appointment")}}</span>
                                         </div>   
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <span>Due After</span>
                                        {!! Form::time('due_after[]', null, ['class' => 'form-control due_after', 'placeholder' => 'Due After']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <span>Due Before</span>
                                        {!! Form::time('due_before[]', null, ['class' => 'form-control due_before','placeholder' => 'Due Before']) !!}
                                    </div> --}}
                                </div>
                                
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-4" id="onlyFirst">
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
                    <a href="#" class="add-sub-task-btn waves-effect waves-light subTaskHeader">{{__("Add Sub Task")}}</a>
                </div>
            </div>

            <!-- end row -->

            <!-- container -->
            <h4 class="header-title mb-2">{{__("Task Description")}}</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::hidden('recipient_phone', null, ['class' => 'form-control rec', 'placeholder' =>
                        __('Recipient Phone'), 'required' => 'required']) !!}
                        {!! Form::hidden('recipient_email', null, ['class' => 'form-control rec', 'placeholder'
                        => __('Recipient Email'), 'required' => 'required']) !!}
                            {!! Form::textarea('task_description', null, ['class' => 'form-control',
                            'placeholder' => __('Please enter task description'), 'rows' => 2, 'cols' => 40]) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                       
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6 mb-3">
                   
                    <div class="form-group text-center" id="colorInput">
                        <label class="btn btn-info width-lg waves-effect waves-light newchnageimage upload-img-btn">
                            <span><i class="fas fa-image mr-2"></i>{{__("Upload Image")}}</span>
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

            @if($preference->route_alcoholic_input == 1)
            <div class="row">
                <div class="col-12 my-2">
                    <div class="custom-switch redio-all">
                        <input type="checkbox" value="1" class="custom-control-input large-icon" id="alcoholic_item" name="alcoholic_item" >
                        <label class="custom-control-label checkss" for="alcoholic_item">{{__("Alcoholic Item")}}</label>
                    </div>
                </div>
            </div>
            @endif

            <h4 class="header-title mb-3">{{__("Call Back URL")}}</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::text('call_back_url', null, ['class' => 'form-control rec', 'placeholder' => __('Call Back URL')]) !!}
                    </div>
                </div>
            </div>

            <h4 class="header-title mb-2">{{__("Allocation")}}</h4>
            <div class="row" id="rediodiv">
                <div class="col-md-8">
                    <div class="login-form">
                         <ul class="list-inline">
                            <li class="d-inline-block mr-2">
                                <input type="radio" class="custom-control-input check assignRadio" id="customRadio"
                                name="allocation_type" value="u" {{$allcation->manual_allocation == 0 ?'checked':''}}>
                            <label class="custom-control-label" for="customRadio">{{__("Unassigned")}}</label>
                            </li>
                            <li class="d-inline-block mr-2">
                                <input type="radio" class="custom-control-input check assignRadio" id="customRadio22"
                                name="allocation_type" value="a" {{$allcation->manual_allocation == 1 ?'checked':''}}>
                            <label class="custom-control-label" for="customRadio22">{{__("Auto Allocation")}}</label>
                            </li>
                            <li class="d-inline-block">
                                <input type="radio" class="custom-control-input check assignRadio" id="customRadio33"
                                name="allocation_type" value="m">
                            <label class="custom-control-label" for="customRadio33">{{__("Manual")}}</label>
                            </li>
                         </ul>
                        </div>
                </div>
                <div class="col-md-4">
                    <input class="form-control" type="text" placeholder={{__("Cash to be collected")}} name="cash_to_be_collected">
                </div>
              
            </div>
            <span class="span1 tagspan">{{__("Please select atlest one tag for driver and agent")}}</span>
            <div class="tags {{ $allcation->manual_allocation == 0 ? "hidealloction":""}}">
                <div class="row ">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("Team Tag")}}</label>
                            <select name="team_tag[]" id="selectize-optgroups" class="selectizeInput" multiple placeholder={{__("Select tag...")}}>
                                <option value="">{{__("Select Tag...")}}</option>
                                @foreach ($teamTag as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("Driver Tag")}}</label>
                            <select name="agent_tag[]" id="selectize-optgroup" class="selectizeInput" multiple placeholder={{__("Select tag...")}}>
                                <option value="">{{__("Select Tag...")}}</option>
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
                        <label>{{__("Drivers")}}</label>
                        <select class="form-control" name="agent" id="driverselect">
                            @foreach ($agents as $item)
                                @php
                                    $checkAgentActive = ($item->is_available == 1) ? ' ('.__('Online').')' : ' ('.__('Offline').')';
                                @endphp
                                <option value="{{ $item->id }}">{{ ucfirst($item->name) . $checkAgentActive }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>