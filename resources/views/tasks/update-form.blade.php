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
                        <input type="text" id='search' class="form-control" name="search" placeholder="earch Customer" value="{{$task->customer->name}}">
                        <input type="hidden" id='cusid' name="ids" value="{{$task->customer->id}}" readonly>

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
                        <select class="form-control" id="task_type" name="task_type_id">
                           
                                <option value="1" {{$task->task->task_type_id == 1 ? 'selected' :''}}>Pickup</option>
                                <option value="2" {{$task->task->task_type_id == 2 ? 'selected' :''}}>Drop</option>
                                <option value="3" {{$task->task->task_type_id == 3 ? 'selected' :''}}>Appintment</option>
                           
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
                    
                    <div class="form-group" id="typeInputss">
                        @foreach ($task->customer->location as $item)
                    <div class="append"><label for="title" class="control-label mt-2">{{$item->short_name}}</label><div class="custom-control custom-radio"><input type="radio" id="{{$item->id}}" name="old_address_id" value="{{$item->id}}" {{$task->task->location_id == $item->id ? 'checked':'' }} class="custom-control-input"><label class="custom-control-label" for="{{$item->id}}">{{$item->address}}</label></div></div>
                        @endforeach
                    </div>  
                </div>
            </div>
            <h4 class="header-title mb-3">Meta Data</h4>
            <div class="row">
                <div class="col-md-6">
                <div class="form-group" id="make_modelInput">
                <input type="text" class="form-control rec" name="recipient_phone" placeholder="recipient_phone" value="{{$task->recipient_phone}}">
                        <input type="email" class="form-control rec" name="recipient_email" placeholder="recipient_email" value="{{$task->Recipient_email}}">
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
                <div class="col-12 upload">
                    <div class="upload">
                        <label style="font-size: 14px;">
                            
                        </label>
                       
                        <!--To give the control a modern look, I have applied a stylesheet in the parent span.-->
                        <span class="btn btn-success fileinput-button">
                            <span>Select Task Images</span>
                            <input type="file" name="files[]" id="files" multiple accept="image/jpeg, image/png, image/gif,"><br />
                        </span>
                        <output id="Filelist">
                            <ul class="thumb-Images" id="imgList">
                                @foreach ($images as $item)
                            <li><div class="img-wrap"> <span class="close">×</span><img class="thumb" src="{{ asset('taskimage/'. $item .'')}}" ></div><div class="FileNameCaptionStyle"></div></li>
                                @endforeach
                                
                            </ul>
                        </output>
                    </div>
                </div><!-- end col -->
            </div>
        <h4 class="header-title mb-3">Allocation</h4>
        <div class="row my-3" id="rediodiv">
        <div class="col-md-4 padd">
            <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input check" id="customRadio" name="allocation_type" value="Un-Assigend" {{$task->task->allocation_type == 'Un-Assigend' ? 'checked':'' }}>
            <label class="custom-control-label" for="customRadio">Un-Assigend</label>
            </div>
        </div>  
        <div class="col-md-4 padd">  
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input check" id="customRadio22" name="allocation_type" value="auto" {{$task->task->allocation_type == 'auto' ? 'checked':'' }}>
                <label class="custom-control-label" for="customRadio22">Auto Alloc</label>
            </div>
        </div>
        <div class="col-md-4 padd">    
            <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input check" id="customRadio33" name="allocation_type" value="Manual" {{$task->task->allocation_type == 'Manual' ? 'checked':'' }}>
            <label class="custom-control-label" for="customRadio33">Manual</label>
            </div>
        </div>
        </div>
        <div class="row tags">
            <div class="col-md-6">
                <div class="form-group mb-3">
                     <label>Team Tag</label>
                     <select  name="team_tag[]" id="selectize-optgroups" multiple placeholder="Select tag...">
                         <option value="">Select Tag...</option>
                         @foreach ($teamTag as $item)
                         <option value="{{$item->id}}" {{in_array($item->id, $saveteamtag)?'selected':''}}>{{$item->name}}</option>
                         @endforeach
     
                     </select>
                  </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Driver Tag</label>
                    <select name="agent_tag[]" id="selectize-optgroup"  multiple placeholder="Select tag...">
                    <option value="">Select Tag...</option>
                    @foreach ($agentTag as $item)
                    <option value="{{$item->id}}" {{in_array($item->id, $savedrivertag)?'selected':''}}>{{$item->name}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
       </div>

    <div class="row drivers">
        <div class="col-md-12">
           <div class="form-group mb-3">
            <label>Drivers</label>
               <select class="form-control" name="agent" id="location_accuracy">
                @foreach ($agents as $item)
                <option value="{{$item->id}}" {{$task->driver_id == $item->id ? 'selected':''}}>{{$item->name}}</option>
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

