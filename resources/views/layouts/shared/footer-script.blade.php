<!-- bundle -->
<!-- Vendor js -->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<script src="{{asset('assets/js/vendor.min.js')}}"></script>
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
</script>
@yield('script')
<!-- App js -->
<script src="{{asset('assets/js/app.min.js')}}"></script>
@yield('script-bottom')