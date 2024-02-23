<!-- bundle -->
<!-- Vendor js -->
{{--
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"
	type="text/javascript"></script>
--}} @include('modal.modalPopup')

<!-- <div class="nb-spinner-main">
    <div class="nb-spinner"></div>
</div> -->

<script type="text/javascript" src="{{asset('assets/js/axios.min.js')}}"></script>

<script src="{{asset('assets/js/waitMe.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script>
    $(function() {
        $('.select2-multiple').select2();
        
    });
    //
    $(".remove-modal-open").click(function(e) {
        // alert("hello");
        $('body').addClass('modal-opensag');
    });
    
   

    const startLoader = function(element) {
        // check if the element is not specified
        if (typeof element == 'undefined') {
            element = "body";
        }
        // set the wait me loader
        $(element).waitMe({
            effect: 'bounce',
            text: 'Please Wait..',
            bg: 'rgba(255,255,255,0.7)',
            //color : 'rgb(66,35,53)',
            color: '#EFA91F',
            sizeW: '20px',
            sizeH: '20px',
            source: ''
        });
    }



    const stopLoader = function(element) {
        // check if the element is not specified
        if (typeof element == 'undefined') {
            element = 'body';
        }
        // close the loader
        $(element).waitMe("hide");
    }

    // $('#newcheck').click(function(){

    //     //$('.checking').toggleClass('classB', $('#pass').prop('type', 'text'));
    //     // var element = document.getElementById("newcheck");
    //     // element.classList.remove("checking");
    //     // element.classList.add("show");
    //     // $('#pass').prop('type', 'text');
    // });

    $('.showpassword').click(function() {
        var element = document.getElementById("pass");
        var spanid = document.getElementById("newcheck");
        if (element.type == 'password') {
            $('#pass').prop('type', 'text');
            spanid.classList.remove("fe-eye-off");
            spanid.classList.remove("showpassword");
            spanid.classList.add("fe-eye");
            spanid.classList.add("showpassword");

        } else {
            $('#pass').prop('type', 'password');
            spanid.classList.remove("fe-eye");
            spanid.classList.remove("showpassword");
            spanid.classList.add("fe-eye-off");
            spanid.classList.add("showpassword");

        }

    });

    $(document).ready(function() {
        $(document).on('click', '.choose_warehouse', function() {

             var data_id = $(this).attr('data-id');

            if ($(this).text() == "Choose Warehouse") {
                $(this).text("Choose Location");
                $(this).closest(".firstclone1").find(".select_category-field").show();
                $(this).parent().closest('.warehouse_id_'+data_id).addClass('is_warehouse_selected');
            } else {
                $(this).text("Choose Warehouse");
                $(this).closest(".firstclone1").find(".select_category-field").hide();
                $(this).closest(".firstclone1").find(".warehouse").val('');
                $(this).parent().closest('.warehouse_id_'+data_id).removeClass('is_warehouse_selected');            };

            

            $("#alsavedaddress"+data_id).find('.withradio .append').remove();
            $("#alsavedaddress"+data_id).find('.editwithradio .append').remove();
            $(this).closest(".firstclone1").find(".location-section").toggle();
            $(this).closest(".firstclone1").find(".warehouse-fields").toggle();
            $(this).closest(".firstclone1").find(".warehouse-data").toggle();
        });


        
    

    });
        
	
</script>
@yield('script')
<!-- App js -->

<script src="{{asset('assets/js/app.min.js')}}"></script>
<script
	src="{{asset('assets/libs/jquery-toast-plugin/jquery-toast-plugin.min.js')}}"></script>
<script src="{{asset('assets/js/pages/toastr.init.js')}}"></script>
@yield('script-bottom') @yield('popup-js')
