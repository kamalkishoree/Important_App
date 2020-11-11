@extends('layouts.vertical', ['title' => 'Tasks'])

@section('css')
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/multiselect/multiselect.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/selectize/selectize.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet"
    type="text/css" />
<link href="{{asset('assets/libs/clockpicker/clockpicker.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet"
    type="text/css" />
    <link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />

    

<style>
.newAdd{
    margin-top: 16%;
}
.shows{
    display: none;
}
.rec{
    margin-bottom: 7px;
}
.needsclick{

    margin-left: 27%;
}
.padd{
    padding-left: 9% !important;
}
.newchnage{
    margin-left: 27% !important;
}
.address{
    margin-bottom: 6px
}
.tags{
    display: none;
}

</style>


@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-6">
            <div class="page-title-box">
                <h4 class="page-title">Tasks</h4>
            </div>
        </div>
    </div>

    <form id="task_form" action="{{ route('tasks.store') }}" method="POST" class="border-0" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews"
                            data-upload-preview-template="#uploadPreviewTemplate">
       @include('tasks.task-form')
    </form>
</div>
@endsection

@section('script')
<!-- google maps api -->

<!-- Plugins js-->
<script src="{{asset('assets/libs/selectize/selectize.min.js')}}"></script>
<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script src="{{asset('assets/libs/multiselect/multiselect.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js')}}"></script>
<script src="{{asset('assets/libs/jquery-mockjax/jquery-mockjax.min.js')}}"></script>
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>

<!-- Plugins js-->
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('assets/js/pages/form-advanced2.init.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
    <script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>

    <!-- Page js-->
    <script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>

    

<script>
$(document).ready(function(){
    $(".shows").hide();
    $("#AddressInput a").click(function() {
        $(".shows").show();
    });
    
    $("#nameInput").keyup(function() {
        $(".shows").hide();
   });
   $( "#myselect" ).val();
   $(".appointment_date").prop('disabled', true);
   $('#task_type').on('change', function() {
      if(this.value == 3)
      $(".appointment_date").prop('disabled', false);
      else
      $(".appointment_date").prop('disabled', true);
    });
    $(".tags").hide();
    $(".drivers").hide();

        $("input[type='radio'].check").click(function(){
            var radioValue = $("#rediodiv input[type='radio']:checked").val();
            if(radioValue == 'auto'){
               $(".tags").show();
            }
            if(radioValue == 'Un-Assigend'){
               $(".tags").hide();
               $(".drivers").hide();
            }
            if(radioValue == 'Manual'){
               $(".drivers").show();
               $(".tags").hide();
            }
        });

});


</script>
@endsection
