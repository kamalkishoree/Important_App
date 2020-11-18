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
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

    

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
#typeInputss {
    overflow-y: auto;
    height: 142px;
}




input[type="file"] {
  display: block;
}
.imageThumb {
  max-height: 75px;
  border: 2px solid;
  padding: 1px;
  cursor: pointer;
  width: 77px;
}
.pip {
  display: inline-block;
  margin: 10px 10px 0 0;
}
.remove {
  display: block;
  background: #444;
  border: 1px solid black;
  color: white;
  text-align: center;
  cursor: pointer;
}
.remove:hover {
  background: white;
  color: black;
}
.upload{
margin-bottom: 20px;
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
                            data-upload-preview-template="#uploadPreviewTemplate" enctype="multipart/form-data">
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
{{-- <script src="{{asset('assets/libs/jquery-mockjax/jquery-mockjax.min.js')}}"></script> --}}
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
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- Page js-->
    <script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>

    

<script>
$(document).ready(function(){
    $(".shows").hide();
    $("#AddressInput a").click(function() {
        $(".shows").show();
        $(".append").hide();
    });
    
    $("#nameInput").keyup(function() {
        $(".shows").hide();
        $(".oldhide").show();
        $(".append").hide();
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

    var CSRF_TOKEN = $("input[name=_token]").val();
    

      $( "#search" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url:"{{route('search')}}",
            type: 'post',
            dataType: "json",
            data: {
               _token: CSRF_TOKEN,
               search: request.term
            },
            success: function( data ) {
               response( data );
            }
          });
        },
        select: function (event, ui) {
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
            url:"{{route('search')}}",
            type: 'post',
            dataType: "json",
            data: {
               _token: CSRF_TOKEN,
               id: ids
            },
            success: function( data ) {
                var array = data;
                jQuery.each( array, function( i, val ) {
                    $("#typeInputss").append('<div class="append"><label for="title" class="control-label mt-2">' + val.short_name + '</label><div class="custom-control custom-radio"><input type="radio" id="' + val.id + '" name="old_address_id" value="'+ val.id +'" class="custom-control-input"><label class="custom-control-label" for="' + val.id + '">' + val.address + '</label></div></div>');
                });
              
            }
          });
      }


      if (window.File && window.FileList && window.FileReader) {
    $("#files").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          $("<span class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/><span class=\"remove\">X</span>" +
            "</span>").insertAfter("#files");
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
          
          // Old code here
          /*$("<img></img>", {
            class: "imageThumb",
            src: e.target.result,
            title: file.name + " | Click to remove"
          }).insertAfter("#files").click(function(){$(this).remove();});*/
          
        });
        fileReader.readAsDataURL(f);
      }
      console.log(files);
    });
  } else {
    alert("Your browser doesn't support to File API")
  }


      


      

});
</script>
@endsection
