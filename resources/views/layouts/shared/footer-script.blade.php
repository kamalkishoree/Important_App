<!-- bundle -->
<!-- Vendor js -->
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}
@include('modal.modalPopup')

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
            if ($(this).text() == "Choose Warehouse") {
                $(this).text("Choose Location");
                $(this).closest(".firstclone1").find(".select_category-field").show();
            } else {
                $(this).text("Choose Warehouse");
                $(this).closest(".firstclone1").find(".select_category-field").hide();
                $(this).closest(".firstclone1").find(".warehouse").val('');
            };
            $(this).closest(".firstclone1").find(".location-section").toggle();
            $(this).closest(".firstclone1").find(".warehouse-fields").toggle();
        });


        $(document).on('change', '.category_id', function() {
            var cat_id = $(this).val();
            $.ajax({
                url: "/get-inventory-products",
                type: "get",
                datatype: "html",
                data: {
                    cat_id: cat_id
                },
                success: (data) => {
                    $(this).closest(".firstclone1").find(".warehouse").empty().html(data);
                },
                error: () => {
                    $(this).closest(".firstclone1").find(".warehouse").empty().html('Something went wrong');
                },
                complete: function(data) {
                    // hideLoader();
                }
            });
        });

        $(document).on('change', '.inventory_category_id', function() {
            var cat_id = $(this).val();
            $.ajax({
                url: "/get-inventory-products",
                type: "get",
                datatype: "html",
                data: {
                    cat_id: cat_id
                },
                success: (data) => {
                    $(".inventory-products").empty().html(data);
                },
                error: () => {
                    $(".inventory-products").empty().html(data);
                },
                complete: function(data) {
                    // hideLoader();
                }
            });
        });
        var arr = [];
        var product_ids = [];
        var vendor_ids = []; 
        $(document).on('change', '#inventory_product', function() {
            var cat_id = $(this).val();
            $.ajax({
                url: "/get-product-name",
                type: "get",
                datatype: "html",
                data: {
                    cat_id: cat_id
                },
                success: (data) => {
                    if (data != '') {

                        if (!arr.includes(cat_id)) {
                            arr.push(cat_id);
                            $("#selected_inventory_products").append("<option value='" + cat_id + "' selected>" + data + "</option>");
                        }
                       
                        $.each($('#selected_inventory_products').select2('data'), (e, v) => {

                            product_ids.push(parseInt(v.id));
                            getProductWarehouses(product_ids)


                        })

                    }
                },
                error: () => {
                    $("#selected_inventory_products").append("<option value='" + cat_id + "' selected>" + data + "</option>");
                },
                complete: function(data) {
                    // hideLoader();
                }
            });

        });

        function getProductWarehouses(data){
           
            $.ajax({
                url: "/get-selected-warehouses",
                type: "post",
                datatype: "json",
                data: {
                    data: data
                },
                success: (data) => {
                   
                    $(".inventory_vendor").empty();
                    $(".inventory_vendor").append(data);

                },
                error: () => {
                    // $("#selected_inventory_products").append("<option value='" + cat_id + "' selected>" + data + "</option>");
                },
                complete: function(data) {
                    // hideLoader();
                }
            });

        }

    });
</script>
@yield('script')
<!-- App js -->

<script src="{{asset('assets/js/app.min.js')}}"></script>
<script src="{{asset('assets/libs/jquery-toast-plugin/jquery-toast-plugin.min.js')}}"></script>
<script src="{{asset('assets/js/pages/toastr.init.js')}}"></script>
@yield('script-bottom')
@yield('popup-js')