<!-- bundle -->
<!-- Vendor js -->
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}

<script src="{{asset('assets/js/waitMe.min.js')}}"></script>




<script src="{{asset('assets/js/waitMe.min.js')}}"></script>

<script>



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

$('.showpassword').click(function(){
    var element = document.getElementById("pass");
    var spanid  = document.getElementById("newcheck");
    if(element.type == 'password'){
        $('#pass').prop('type', 'text');
        spanid.classList.remove("fe-eye-off");
        spanid.classList.remove("showpassword");
        spanid.classList.add("fe-eye");
        spanid.classList.add("showpassword");
        
    }else{
        $('#pass').prop('type', 'password');
        spanid.classList.remove("fe-eye");
        spanid.classList.remove("showpassword");
        spanid.classList.add("fe-eye-off");
        spanid.classList.add("showpassword");
        
    }
     
});

</script>
@yield('script')
<!-- App js -->

<script src="{{asset('assets/js/app.min.js')}}"></script>

@yield('script-bottom')