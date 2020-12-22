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
                    <h4 class="page-title">Edit Task</h4>
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
                        <div class="row d-flex align-items-center" id="dateredio">
                            <div class="col-md-3">
                                <h4 class="header-title mb-3">Customer</h4>
                            </div>
                            <div class="col-md-5 text-right">
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
                            <div class="col-md-4 datenow">
                                <input type="text" id='datetime-datepicker' name="schedule_time"
                                    class="form-control upside" placeholder="Date Time">
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
                                        {!! Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => 'Phone Number',
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
                        @php
                            $newcount = 0;
                        @endphp
                        <div class="taskrepet" id="newadd">
                          @foreach ($task->task as $keys => $item)
                           @php $maincount = 0; $newcount++; @endphp
                        <div class="copyin{{$keys == 0?'1':''}}" id="copyin1">
                            <div class="requried allset">
                              <div class="row firstclone1">
                                 
                                  <div class="col-md-6">
                                      <div class="form-group mb-3">
                                          <select class="form-control selecttype mt-1 taskselect" id="task_type"  name="task_type_id[]" required>
                                              <option value="">Selcet Task </option>
                                              <option value="1" {{$item->task_type_id == 1 ? 'selected' :''}}>Pickup Task</option>
                                              <option value="2" {{$item->task_type_id == 2 ? 'selected' :''}}>Drop Off Task</option>
                                              <option value="3" {{$item->task_type_id == 3 ? 'selected' :''}}>Appointment</option>

                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-md-5">
                                      <div class="form-group {{$item->task_type_id == 3 ? 'newclass' :'appoint'}}">
                                        <input type="text" class="form-control appointment_date" name="appointment_date[]" placeholder="Duration (In Min)" value="{{$item->allocation_type}}">
                                          <span class="invalid-feedback" role="alert">
                                              <strong></strong>
                                          </span>
                                      </div>


                                  </div>
                                  <div class="col-md-1 " >

                                    <span class="span1 onedelete" id="spancheck"><img style="filter: grayscale(.5);" src="{{asset('assets/images/ic_delete.png')}}" alt=""></span>


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
                                  <div class="col-md-6 cust_add_div" id="add{{$newcount}}">
                                      <div class="form-group alladdress" id="typeInput">
                                          {!! Form::text('short_name[]', null, ['class' => 'form-control address',
                                          'placeholder' => 'Address Short Name']) !!}

                                            <div class="form-group input-group" id="addressInput">
                                                <input type="text" id="add{{$newcount}}-input" name="address[]" class="form-control cust_add" placeholder="Address">
                                                <div class="input-group-append">
                                                    <button class="btn btn-xs btn-dark waves-effect waves-light showMapTask cust_btn" type="button" num="add{{$newcount}}"> <i class="mdi mdi-map-marker-radius"></i></button>
                                                </div>
                                                <input type="hidden" name="latitude[]" id="add{{$newcount}}-latitude" class="cust_latitude" value="0" />
                                                <input type="hidden" name="longitude[]" id="add{{$newcount}}-longitude" class="cust_longitude" value="0" />
                                                <span class="invalid-feedback" role="alert" id="address">
                                                    <strong></strong>
                                                </span>
                                            </div>

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
                                    <label class="btn btn-info width-lg waves-effect waves-light newchnageimage upload-img-btn">
                                        <span><i class="fas fa-image mr-2"></i>Upload Image</span>
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
                            <div class="col-md-12">
                                <div class="login-form">
                                    <ul class="list-inline">
                                        <li class="d-inline-block mr-2">
                                            <input type="radio" class="custom-control-input check assignRadio" id="customRadio"
                                            name="allocation_type" value="u" {{$task->auto_alloction == 'u'?'checked':''}}>
                                        <label class="custom-control-label" for="customRadio">Unassigned</label>
                                        </li>
                                        <li class="d-inline-block mr-2">
                                            <input type="radio" class="custom-control-input check assignRadio" id="customRadio22"
                                            name="allocation_type" value="a" {{$task->auto_alloction == 'a'?'checked':''}}>
                                        <label class="custom-control-label" for="customRadio22">Auto Allocation</label>
                                        </li>
                                        <li class="d-inline-block">
                                            <input type="radio" class="custom-control-input check assignRadio" id="customRadio33"
                                            name="allocation_type" value="m" {{$task->auto_alloction == 'm'?'checked':''}}>
                                        <label class="custom-control-label" for="customRadio33">Manual</label>
                                        </li>
                                      </ul>
                                    </div>
                            </div>
                        </div>
                        <span class="span1 tagspan">Please select atlest one tag for driver and agent</span>
                        <div class="tags">
                        <div class="row">
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

    <div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
    <script src="{{ asset('assets/js/pages/form-advanced2.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    
    <script src="{{ asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/clockpicker/clockpicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    <!-- Page js-->




    <script>
        var autocomplete = {};
        var countEdit = parseInt('{{$task->task->count()}}');
    var autocompletesWraps = [];
     editCount = 0;
    $(document).ready(function(){
        console.log(countEdit);
       // autocompletesWraps.push('add1');

        for (var i = 1; i <= countEdit; i++) {
            autocompletesWraps.push('add'+i);
            loadMap(autocompletesWraps);
        }
        loadMap(autocompletesWraps);
    });
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
                autoWrap = ['add1'];
                countEdit = 1;

            });
            $("#Inputsearch a").click(function() {
                $(".shows").hide();
                $(".append").hide();
                $(".searchshow").show();
                $('.copyin').remove();
                autoWrap = ['add1'];
                countEdit = 1;

            });

            $("#nameInput").keyup(function() {
                $(".shows").hide();
                $(".oldhide").show();
                $(".append").hide();
                $('input[name=ids').val('');
                $('.copyin').remove();
                autoWrap = ['add1'];
                countEdit = 1;

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
                countEdit = countEdit + 1;
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

                        $clone.find('.cust_add_div').prop('id', 'add' + countEdit);
                        $clone.find('.cust_add').prop('id', 'add' + countEdit +'-input');
                        $clone.find('.cust_btn').prop('num', 'add' + countEdit);
                        $clone.find('.cust_latitude').prop('id', 'add' + countEdit +'-latitude');
                        $clone.find('.cust_longitude').prop('id', 'add' + countEdit +'-longitude');


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

                autocompletesWraps.indexOf('add'+countEdit) === -1 ? autocompletesWraps.push('add'+countEdit) : console.log("exists");
                loadMap(autocompletesWraps);
                
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
              case "a":
              $(".tags").show();
                break;
              case "m":
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

    function loadMap(autocompletesWraps){

        console.log(autocompletesWraps);
        $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder; 

            if($('#'+name).length == 0) {
                return;
            }
            //autocomplete[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); console.log('hello');
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name+'-input'), { types: ['geocode'] });
            google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
                
                var place = autocomplete[name].getPlace();

                geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                    
                    if (status === google.maps.GeocoderStatus.OK) {
                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();
                        console.log(latitudes);
                        document.getElementById(name + '-latitude').value = lat;
                        document.getElementById(name + '-longitude').value = lng;
                    }
                });
            });
        });

    }

    $(document).on('click', '.showMapTask', function(){
        var no = $(this).attr('num');
        console.log(no);
        var lats = document.getElementById(no+'-latitude').value;
        var lngs = document.getElementById(no+'-longitude').value;
        console.log(lats);
        console.log(lngs);
        document.getElementById('map_for').value = no;

        if(lats == null || lats == '0'){
            lats = 51.508742;
        }
        if(lngs == null || lngs == '0'){
            lngs = -0.120850;
        }

        var myLatlng = new google.maps.LatLng(lats, lngs);
            var mapProp = {
                center:myLatlng,
                zoom:13,
                mapTypeId:google.maps.MapTypeId.ROADMAP
              
            };
            var map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
                var marker = new google.maps.Marker({
                  position: myLatlng,
                  map: map,
                  title: 'Hello World!',
                  draggable:true  
              });
            document.getElementById('lat_map').value= lats;
            document.getElementById('lng_map').value= lngs ; 
            // marker drag event
            google.maps.event.addListener(marker,'drag',function(event) {
                document.getElementById('lat_map').value = event.latLng.lat();
                document.getElementById('lng_map').value = event.latLng.lng();
            });

            //marker drag event end
            google.maps.event.addListener(marker,'dragend',function(event) {
                var zx =JSON.stringify(event);
                console.log(zx);


                document.getElementById('lat_map').value = event.latLng.lat();
                document.getElementById('lng_map').value = event.latLng.lng();
                //alert("lat=>"+event.latLng.lat());
                //alert("long=>"+event.latLng.lng());
            });
            $('#add-customer-modal').addClass('fadeIn');
        $('#show-map-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });

    });

    $(document).on('click', '.selectMapLocation', function () {

        var mapLat = document.getElementById('lat_map').value;
        var mapLlng = document.getElementById('lng_map').value;
        var mapFor = document.getElementById('map_for').value;
        console.log(mapLat+'-'+mapLlng+'-'+mapFor);
        document.getElementById(mapFor + '-latitude').value = mapLat;
        document.getElementById(mapFor + '-longitude').value = mapLlng;


        $('#show-map-modal').modal('hide');
    });

    </script>
@endsection
