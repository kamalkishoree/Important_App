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
            $(this).closest(".firstclone1").find(".warehouse-data").toggle();
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
        
        $("#get-warehouse").click(function()
        {
        
           getProductWarehouses(product_ids)
        
        });

        function getProductWarehouses(data) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/get-selected-warehouses",
                type: "post",
                datatype: "json",
                data: {
                    data: data
                },
                success: (data) => {

                    $(".inventory_warehouse").empty();
                    $(".inventory_warehouse").append(data);

                },
                error: () => {
                    // $("#selected_inventory_products").append("<option value='" + cat_id + "' selected>" + data + "</option>");
                },
                complete: function(data) {
                    // hideLoader();
                }
            });

        }

        var warehouse_id = [];
        var vendor_id = [];
        var item_count = [];
        var list= [];
        $("#create_subtask").click(function(e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
             
      
            $("input[name='product_id']").each(function() {
            
                if(!warehouse_id.includes(parseInt($(this).val()))){
                   warehouse_id.push(parseInt($(this).val()));
                }
            });
 
 
            $("input[name='product_id']").each(function() {
            
                  vendor_id.push({'warehouse_id':parseInt($(this).attr('data-id')),'product_id':parseInt($(this).val())});
              
            });
         
         console.log(vendor_id);
            autoWrap.indexOf('addHeader1') === -1 ? autoWrap.push('addHeader1') : '';
            e.preventDefault();

            $.ajax({
                type: "post",
                url: "{{ route('getWarehouseProducts')}}",
                data: {
                    'product_id' : warehouse_id,
                    'vendor_id' : vendor_id
                },
                dataType: 'json',
                success: function(data) {
                    $('.submitTaskHeaderLoader').css('display', 'none');
                    $('#submitTaskHeaderText').text('Submit');
                    $('.submitTaskHeader').removeClass("inactiveLink");

                    $('#task-modal-header #addCardBox').html(data.html);

                    $('#task-modal-header').find('.selectizeInput').selectize();


                    $('.dropify').dropify();
                    $(".newcustomer").hide();
                    $(".searchshow").show();
                    $(".append").show();
                    $('.copyin').remove();

                    $(".addspan").hide();
                    $(".tagspan").hide();
                    $(".tagspan2").hide();
                    $(".searchspan").hide();
                    $(".datenow").hide();

                    $(".pickup-barcode-error").hide();
                    $(".drop-barcode-error").hide();
                    $(".appointment-barcode-error").hide();

                    $('.appoint').hide();

                    loadMapHeader(autoWrap);
                    searchRes();
                    $('#task-modal-header').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    phoneInput();
                    runPicker();

                    $('#task-modal-header .edit-icon-float-right').on('click', function() {
                        $('#task-modal-header .meta_data_task_div').toggle();
                        if ($(this).find('i').hasClass('mdi mdi-chevron-down')) {
                            $(this).find('i').removeClass('mdi mdi-chevron-down');
                            $(this).find('i').addClass('mdi mdi-chevron-up');
                        } else {
                            $(this).find('i').removeClass('mdi mdi-chevron-up');
                            $(this).find('i').addClass('mdi mdi-chevron-down');
                        }
                    });


                },
                error: function(data) {}
            });



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
