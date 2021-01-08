@extends('layouts.vertical', ['title' => 'Task'])

@section('css')
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />



<!-- for File Upload -->

<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
<style>
// workaround
.intl-tel-input {
  display: table-cell;
}

.inner-div {
        width: 50%;
        float: left;
    }
.intl-tel-input .selected-flag {
  z-index: 4;
}
.intl-tel-input .country-list {
  z-index: 5;
}
.input-group .intl-tel-input .form-control {
  border-top-left-radius: 4px;
  border-top-right-radius: 0;
  border-bottom-left-radius: 4px;
  border-bottom-right-radius: 0;
}
#radio1, #radio2, #radio3, #radio4 {  
    -ms-transform: scale(1.2); /* IE 9 */
    -webkit-transform: scale(1.2); /* Chrome, Safari, Opera */
    transform: scale(1.2); }
.showspan{
    font-weight: normal;
}
.showtasks{
    border: none;
    outline:none;
}


.assigned-block {
  background-color: #EAECFD;
}
.assigned-block h6 {
  color: #272727;
  font-size: 13px;
  letter-spacing: 0;
  line-height: 15px;
  margin-bottom: 0px;
  margin-top: 4px;
} 
.assigned-block span {
  color: #797979;
  font-size: 13px;
  letter-spacing: 0;
}
.assigned-block h5 {
   color: #272727;
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0;
}
.assigned-block a {
  color: #797979;
  font-size: 11px;
  font-weight: 500;
  letter-spacing: 0;
  border-bottom: 1px dashed #979797;
}
.assigned-btn {
  border-radius: 10px;
  background-color: rgb(44 129 255 / .21);
  color: #5664EA;
  padding: 4px 10px;
  font-size: 11px;
  font-weight: 500;
  letter-spacing: 0;
  line-height: 13px;
  border: none;
}
.wd-10{
    width: 6%;
    display: inline-block;
    vertical-align: top;
    padding-top: 4px;
}
.wd-90{
    width: 89%;
    display: inline-block;
}
</style>
@endsection
@include('modals.task-list')
@section('content')
<!-- Start Content-->

@endsection

@section('script')

<!-- Plugins js-->

<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<!-- Page js-->

  
<script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
   

<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<!-- Page js-->

<script src="{{asset('assets/js/storeAgent.js')}}"></script>
<!-- for File Upload -->

<!-- Page js-->

<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>

<script>



$(document).ready( function () {
    $('#agents-datatable').DataTable();
});

    function handleClick(myRadio) {
        $('#getTask').submit();
    }

    $(document).on('click', '.showtasks', function () {
      var CSRF_TOKEN = $("input[name=_token]").val();
      var tour_id = $(this).val();
      var basic = window.location.origin;
      var url = basic+"/tasks/list/"+tour_id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
            _token: CSRF_TOKEN,
            },
            success: function(data) {
                
                // console.log(data[0].task);
                 //abc = $('#removedata').html('');
                //console.log(abc);
                // $('#removedata').hide();
                $('.repet').remove();
                var taskname = '';
                $.each(data, function(index, elem){
                  $.each(elem.task,function(indexs, tasks){
                      console.log(tasks.location);
                    switch (tasks.task_type_id) {
                        case 1:
                              taskname = 'Pickup task';
                            break;
                        case 2:
                              taskname = 'Drop Off task';
                            break;
                        case 3:
                              taskname = 'Appointment';
                            break;
                    }
                    var date = new Date(elem.order_time);
                    var options = { hour12: true };
                    $(document).find('.allin').before('<div class="repet"><div class="task-card p-3"><div class="p-2 assigned-block"><h5>'+taskname+'</h5><div class="wd-10"><img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}"></div><div class="wd-90"><h6>'+tasks.location.address+'</h6><span>'+tasks.location.short_name+'</span><h5 class="mb-1"><span></span></h5><div class="row"><div class="col-md-6"></div><div class="col-md-6 text-right"><button class="assigned-btn">'+elem.status+'</button></div></div></div></div></div></div>');
                  });
                         
                });

                $('#task-list-modal').modal('show');
                 
            }
                            
        });
    });
        






</script>

@endsection