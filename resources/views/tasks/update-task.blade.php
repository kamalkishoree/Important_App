@extends('layouts.vertical', ['title' => 'Tasks'])
@section('css')
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/clockpicker/clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">



    <style>
        #adds {
            margin-bottom: 14px;
        }

        .shows {
            display: none;
        }

        .rec {
            margin-bottom: 7px;
        }

        .needsclick {

            margin-left: 27%;
        }

        .padd {
            padding-left: 9% !important;
        }

        .newchnage {
            margin-left: 27% !important;
        }

        .address {
            margin-bottom: 6px
        }

        .tags {
            display: none;
        }

        #typeInputss {
            overflow-y: auto;
            height: 142px;
        }

        .upload {
            margin-bottom: 20px;
            margin-top: 10px;

        }

        .span1 {
            color: #ff0000;
        }

        .check {
            margin-left: 116px !important;
        }

        .newcheck {
            margin-left: -54px;
        }

        .upside {
            margin-top: -10px;
        }

        .newgap {
            margin-top: 11px !important;
        }

        

        .append {
            margin-bottom: 15px;
        }

        .spanbold {
            font-weight: bolder;
        }

        .copyin {
            background-color: #F7F8FA;
        }
        .copyin1 {
            background-color: #F7F8FA;
        }
        hr.new3 {
         border-top: 1px dashed white;
       }
       #spancheck{
           display: none;
       }
       .imagepri{
        min-width: 50px;
           height: 50px;
           width: 50px;
           border-style: groove;
           margin-left: 5px;
           margin-top: 5px;
       }
       .withradio{
       
        
       }
       .showsimage{
        margin-top: 9px;
        margin-left: 140px;
       }
       .showshadding{
        margin-left: 98px;
       }
       .newchnageimage{
           margin-left: 100px;
       }
       .showsimagegall{
        margin-left: 148px;
        margin-top: 21px;

       }
       .allset{
           margin-left: 9px !important;
           padding-top: 10px;
       }



    </style>


@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-box">
                    <h4 class="page-title">Add Task</h4>
                </div>
            </div>
        </div>
        <!-- start page title -->

        <!-- end page title -->
        {!! Form::model($task, ['route' => ['tasks.update', $task->id],'enctype' => 'multipart/form-data']) !!}
        {{ method_field('PATCH') }}
            @csrf
            <div class="row">

                <div class="col-md-6">
                    <div class="card-box">
                        @csrf
                        <div class="row" id="dateredio">
                            <div class="col-md-3">
                                <h4 class="header-title mb-3">Customer</h4>
                            </div>
                            <div class="col-md-2">
                                <span class="header-title mb-4">Task Date:</span>
                            </div>
                            <div class="col-md-2">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input check" id="tasknow"
                                name="task_type" value="now" {{$task->order_type == 'now'?'checked':'' }}>
                                    <label class="custom-control-label" for="tasknow">Now</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input check" id="taskschedule"
                                        name="task_type" value="schedule" {{$task->order_type == 'schedule'?'checked':'' }} >
                                    <label class="custom-control-label" for="taskschedule"></label>
                                </div>
                            </div>
                            <div class="col-md-4 datenow">
                                <input type="text" id='datetime-datepicker' name="schedule_time"
                            class="form-control upside" placeholder="DateTime" value="{{$task->order_time != null ? $task->order_time :'' }}">
                            </div>
                        </div>

                        <span class="span1 searchspan">Please search a customer or add a customer</span>
                        <div class="row searchshow">
                            <div class="col-md-8">
                                <div class="form-group" id="nameInput">

                                    <input type="text" id='search' class="form-control" name="search" placeholder="search Customer" value="{{$task->customer->name}}">
                                    <input type="hidden" id='cusid' name="ids" value="{{$task->customer->id}}" readonly>

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
                                    {!! Form::text('phone_number', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Phone Number',
                                    ]) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="Inputsearch">
                                    <a href="#" class=" form-control btn btn-blue waves-effect waves-light">Previous</a>

                                </div>

                            </div>
                        </div>

                        <div class="taskrepet" id="newadd">
                          @foreach ($task->task as $keys => $item)
                           @php $maincount = 0; @endphp
                        <div class="copyin{{$keys == 0?'1':''}}" id="copyin1">
                            <div class="requried allset">
                              <div class="row firstclone1">
                                  <div class="col-md-4">
                                      <h4 class="header-title mb-3 newgap">Task</h4>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group mb-3">
                                          <select class="form-control selecttype" id="task_type"  name="task_type_id[]" required>
                                              <option value="">Selcet Task </option>
                                              <option value="1" {{$item->task_type_id == 1 ? 'selected' :''}}>Pickup</option>
                                              <option value="2" {{$item->task_type_id == 2 ? 'selected' :''}}>Drop</option>
                                              <option value="3" {{$item->task_type_id == 3 ? 'selected' :''}}>Appintment</option>

                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                      <div class="form-group {{$item->task_type_id == 3 ? 'newclass' :'appoint'}}">
                                        <input type="text" class="form-control appointment_date" name="appointment_date[]" placeholder="Duration (In Min)" value="{{$item->allocation_type}}">
                                          <span class="invalid-feedback" role="alert">
                                              <strong></strong>
                                          </span>
                                      </div>


                                  </div>
                                  <div class="col-md-1 " >

                                    <span class="span1 onedelete" id="spancheck"><img src="{{asset('assets/images/ic_delete.png')}}" alt=""></span>


                                  </div>

                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                    <h4 class="header-title mb-2">Address</h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="header-title mb-2">Saved Addresses</h4>
                                </div>
                            </div>
                              <span class="span1 addspan">Please select a address or create new</span>

                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group alladdress" id="typeInput">
                                          {!! Form::text('short_name[]', null, ['class' => 'form-control address',
                                          'placeholder' => 'Address Short Name']) !!}
                                          {!! Form::textarea('address[]', null, ['class' => 'form-control address',
                                          'placeholder' => 'Full Address', 'rows' => 2]) !!}
                                          {!! Form::text('post_code[]', null, [
                                          'class' => 'form-control address',
                                          'placeholder' => 'PostsCode',
                                          ]) !!}
                                          <span class="invalid-feedback" role="alert">
                                              <strong></strong>
                                          </span>
                                      </div>

                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group withradio" id="typeInputss">

                                        <div class="oldhide">
                                               
                                            <img class="showsimage" src="{{url('assets/images/ic_location_placeholder.png')}}" alt="">
                                        </div>
                                        @foreach ($task->customer->location as $key => $items)
                                        
                                      <div class="append"><div class="custom-control custom-radio"><input type="radio" id="{{$keys}}{{$items->id}}{{12}}" name="old_address_id{{$keys!=0?$keys:''}}" value="{{$items->id}}" {{$item->location_id == $items->id ? 'checked':'' }} class="custom-control-input redio"><label class="custom-control-label" for="{{$keys}}{{$items->id}}{{12}}"><span class="spanbold">{{$items->short_name}}</span>-{{$items->address}}</label></div></div>
                                        
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
                                <a href="#" class="btn btn-block btn-sm btn-success waves-effect waves-light">Add Sub
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
                                    'Recipient Phone', 'required' => 'required']) !!}
                                    {!! Form::email('Recipient_email', null, ['class' => 'form-control rec', 'placeholder'
                                    => 'Recipient Email', 'required' => 'required']) !!}
                                    {!! Form::textarea('task_description', null, ['class' => 'form-control',
                                    'placeholder' => 'Task_description', 'rows' => 2, 'cols' => 40]) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="colorInput">
                                    <label class="btn btn-info width-lg waves-effect waves-light newchnageimage">
                                        <span><i class="fas fa-image"></i></span>
                                        <input id="file" type="file" name="file[]" multiple style="display: none"/>
                                    </label>
                                    @if(!isset($images))
                                    <img class="showsimagegall" src="{{url('assets/images/ic_image_placeholder.png')}}" alt="">
                                    @endif
                                    
                                   
                                    <div class="allimages">
                                      <div id="imagePreview" class="privewcheck">
                                          @foreach ($images as $item)
                                          
                                          <img src="{{$main}}{{$item}}" class="imagepri" />  
                                          @endforeach
                                      </div>
                                    </div>
                                </div>

                            </div>
                        </div>



                        <h4 class="header-title mb-3">Allocation</h4>
                        <div class="row my-3" id="rediodiv">
                            <div class="col-md-4 padd">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input check" id="customRadio"
                                name="allocation_type" value="Un-Assigend" {{$task->auto_alloction == 'Un-Assigend'?'checked':''}}>
                                    <label class="custom-control-label" for="customRadio">Un-Assigned</label>
                                </div>
                            </div>
                            <div class="col-md-4 padd">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input check" id="customRadio22"
                                        name="allocation_type" value="auto" {{$task->auto_alloction == 'auto'?'checked':''}}>
                                    <label class="custom-control-label" for="customRadio22">Auto Allocation</label>
                                </div>
                            </div>
                            <div class="col-md-4 padd">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input check" id="customRadio33"
                                        name="allocation_type" value="Manual" {{$task->auto_alloction == 'Manual'?'checked':''}}>
                                    <label class="custom-control-label" for="customRadio33">Manual</label>
                                </div>
                            </div>
                        </div>
                        <span class="span1 tagspan">Please select atlest one tag for driver and agent</span>
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
                            
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-block btn-lg btn-blue waves-effect waves-light">Submit</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            {!! Form::close() !!}

        


    </div>
@endsection


@section('script')
    <!-- google maps api -->

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/multiselect/multiselect.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/libs/jquery-mockjax/jquery-mockjax.min.js') }}">
    </script> --}}
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced2.init.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/clockpicker/clockpicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- Page js-->




    <script>
        $(document).ready(function() {
            $(".shows").hide();
            $(".addspan").hide();
            $(".tagspan").hide();
            $(".tagspan2").hide();
            $(".searchspan").hide();
            $(".appoint").hide();
            $(".datenow").hide();
            $(".oldhide").hide();
            $("#AddressInput a").click(function() {
                $(".shows").show();
                $(".append").hide();
                $(".searchshow").hide();
                $('input[name=ids').val('');
                $('input[name=search').val('');
                $('.copyin').remove();
            });
            $("#Inputsearch a").click(function() {
                $(".shows").hide();
                $(".append").hide();
                $(".searchshow").show();
                $('.copyin').remove();
            });

            $("#nameInput").keyup(function() {
                $(".shows").hide();
                $(".oldhide").show();
                $(".append").hide();
                $('input[name=ids').val('');
                $('.copyin').remove();
            });
            $("#file").click(function() {
                $('.imagepri').remove();
                
            });
            
            $(document).on('click', ".span1", function() {
                
                $(this).closest(".copyin").remove();
            });
            // $('#adds a').click(function() {
            //     var regex = /^(.+?)(\d+)$/i;
            //     var cloneIndex = $(".copyin").length;
            //     var $div = $('div[id^="copyin1"]:first');
            //     console.log($div);
            //     $('#copyin1').clone().appendTo('.taskrepet')
            //       .attr("id", "copyin" +  cloneIndex)
            //       .find("*")
            //       .each(function() {
            //          var id = this.id || "";
            //          var match = id.match(regex) || [];
            //          if (match.length == 3) {
            //             this.id = match[1] + (cloneIndex);
            //         }
            //       })
            //       .on('click', '.onedelete', remove);
            //     cloneIndex++;
            //     // var button = $('.firstclone').clone();
            //     // console.log()
            //     //$('.taskrepet').html($button);
            //     // var firstDivContent = document.getElementById('typeInputss');
            //     // var secondDivContent = document.getElementById('mydiv2');
            //     // secondDivContent.innerHTML = firstDivContent.innerHTML;

            // });
            var a = 0;
            $('#adds a').click(function() {
              var abc = "{{ isset($maincount)?$maincount:0 }}";
              
               if(a == 0){
                 a = abc;
               }
              
                a++;
               // alert(abc);
                
                // var direction = this.defaultValue < this.value
                // this.defaultValue = this.value;
                // if (direction)
                // {
                        var newids = null;
                        
                        var $div = $('div[class^="copyin"]:last');
                        var newcheck = $div.find('.redio');
                        $.each(newcheck, function(index, elem){
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('checked');
                            var id = jElem.prop('id');
                            if(name == true){
                              newids = id;
                            }
                            
                            
                            // remove the number
                            //name = name.replace(/\d+/g, '');
                            //name += a;
                            //jElem.prop('name', name);
                            //count0++;
                        });
                       // console.log(newcheck);
                        var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
                        var $clone = $div.clone().prop('class', 'copyin')
                        $clone.insertAfter('[class^="copyin"]:last');
                        // get all the inputs inside the clone
                        var inputs = $clone.find('.redio');
                        // for each input change its name/id appending the num value
                        var count0 = 1;
                        $.each(inputs, function(index, elem){
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('name');
                            // remove the number
                            name = name.replace(/\d+/g, '');
                            name += a;
                            jElem.prop('name', name);
                            count0++;
                        });
                       
                        var inputid = $clone.find('.redio');
                        var rand =  Math.random().toString(36).substring(7);
                        var count1 = 1;
                        $.each(inputid, function(index, elem){
                               
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('id');
                            
                            // remove the number
                            name = rand;
                            
                            name += count1;
                            
                            jElem.prop('id', name);
                            jElem.prop('checked', false);
                            count1++;
                        });
                        var count2 = 1;
                        var labels = $clone.find('label');
                        $.each(labels, function(index, elem){
                               
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('for');
                            
                            // remove the number
                            name = rand;
                            
                            name += count2;
                            
                            jElem.prop('for', name);
                            count2++;
                        });
                        var spancheck = $clone.find('.onedelete');
                        $.each(spancheck, function(index, elem){
                               
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('id');
                            name = name.replace(/\d+/g, '');
                            // remove the number
                            name = 'newspan';
                            jElem.prop('id', name);
                        });

                        var address1 = $clone.find('.address');
                        $.each(address1, function(index, elem){
                               
                            var jElem = $(elem); // jQuery element
                            jElem.prop('required', true);
                        });
                        $('input[id='+newids+']').prop("checked",true);
                       // $("input[type='radio'][name='userRadionButtonName']").prop('checked', true);
                        //var everycheck = document.getElementById("#"+newids);
                       
                        //everycheck.prop('checked',true);
                        // $(".taskrepet").fadeOut();
                        // $(".taskrepet").fadeIn();
                // }
                // else $('[id^="newadd"]:last').remove();
            });

            //$("#myselect").val();
            $(document).on('change', ".selecttype", function() {
            
                
                if (this.value == 3){
                   $span = $(this).closest(".firstclone1").find(".appoint").show();
                   console.log($span); 
                }   
                else{
                    $(this).closest(".firstclone1").find(".appoint").hide();
                
                }
                
            });

            $(".callradio input").click(function() { 

                if ($(this).is(":checked")) { 
                  $span = $(this).closest(".requried").find(".alladdress");
                  console.log($span);
                  $(this).parent().css("border", "2px solid black"); 
                }
            });
            $(document).on("click", "input[type='radio']", function () {
            // var element = $(this);
            // alert(element.closest("div").find("img").attr("src"));
            $span = $(this).closest(".requried").find(".address").removeAttr("required");
            // $('#edit-submitted-first-name').removeAttr('required');
            });

            $(".tags").hide();
            $(".drivers").hide();
            $("input[type='radio'].check").click(function() {
                var radioValue = $("#rediodiv input[type='radio']:checked").val();
                if (radioValue == 'auto') {
                    $(".tags").show();
                    $(".drivers").hide();
                }
                if (radioValue == 'Un-Assigend') {
                    $(".tags").hide();
                    $(".drivers").hide();
                }
                if (radioValue == 'Manual') {
                    $(".drivers").show();
                    $(".tags").hide();
                }
            });

            var edit_tag = "{{$task->auto_alloction}}";

            switch(edit_tag) {
              case "auto":
              $(".tags").show();
                break;
              case "Manual":
              $(".drivers").hide();
                break;
              
            }
            var schudle = "{{$task->order_type}}";
            
            if(schudle == 'schedule'){
              $(".datenow").show();
            }

            $("input[type='radio'].check").click(function() {
                var dateredio = $("#dateredio input[type='radio']:checked").val();
                if (dateredio == 'schedule') {
                    $(".datenow").show();
                }else{
                    $(".datenow").hide();
                }
                
            });

            var CSRF_TOKEN = $("input[name=_token]").val();


            $("#search").autocomplete({
                source: function(request, response) {
                    // Fetch data
                    $.ajax({
                        url: "{{ route('search') }}",
                        type: 'post',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    // Set selection
                    $('#search').val(ui.item.label); // display the selected text
                    $('#cusid').val(ui.item.value); // save selected id to input
                    add_event(ui.item.value);
                    $(".oldhide").hide();
                    return false;
                }
            });

            function add_event(ids) {

                $.ajax({
                    url: "{{ route('search') }}",
                    type: 'post',
                    dataType: "json",
                    data: {
                        _token: CSRF_TOKEN,
                        id: ids
                    },
                    success: function(data) {
                        var array = data;
                        jQuery.each(array, function(i, val) {
                            $(".withradio").append(
                                '<div class="append"><div class="custom-control custom-radio count"><input type="radio" id="' +
                                val.id + '" name="old_address_id" value="' + val
                                .id +
                                '" class="custom-control-input redio callradio"><label class="custom-control-label" for="' +
                                val.id + '"><span class="spanbold">' + val.short_name +
                                '</span>-' + val.address +
                                '</label></div></div>');
                        });

                    }
                });
            }

            $("#task_form").bind("submit", function() {
                $(".addspan").hide();
                $(".tagspan").hide();
                $(".tagspan2").hide();
                $(".searchspan").hide();

                var cus_id = $("input[name=ids]").val();
                var name = $("input[name=name]").val();
                var email = $("input[name=email]").val();
                var phone_no = $("input[name=phone_number]").val();

                if (cus_id == '') {
                    if (name != '' && email != '' && phone_no != '') {

                    } else {
                        $(".searchspan").show();
                        return false;
                    }
                }

                var selectedVal = "";
                var selected = $("#typeInputss input[type='radio']:checked");
                selectedVal = selected.val();
                console.log(selectedVal);
                if (typeof(selectedVal) == "undefined") {
                    var short_name = $("input[name=short_name]").val();
                    var address = $("input[name=address]").val();
                    var post_code = $("input[name=post_code]").val();
                    if (short_name != '' && address != '' && post_code != '') {

                    } else {
                        $(".addspan").show();
                        return false;
                    }
                }

                var autoval = "";
                var auto = $("#rediodiv input[type='radio']:checked");
                autoval = auto.val();
                if (autoval == 'auto') {
                    var value = $("#selectize-optgroups option:selected").text();
                    var value2 = $("#selectize-optgroup option:selected").text();
                    if (value == '') {
                        $(".tagspan").show();
                        return false;
                    }
                    if (value2 == '') {
                        $(".tagspan").show();
                        return false;
                    }
                }



            });

            var inputLocalFont = document.getElementById("file");
             inputLocalFont.addEventListener("change",previewImages,false);

             function previewImages(){
              var fileList = this.files;
    
              var anyWindow = window.URL || window.webkitURL;

              for(var i = 0; i < fileList.length; i++){
               var objectUrl = anyWindow.createObjectURL(fileList[i]);
               $('#imagePreview').append('<img src="' + objectUrl + '" class="imagepri" />');
               window.URL.revokeObjectURL(fileList[i]);
               }
    
    
            }


        });

    </script>
@endsection
