<script>
    var autocomplete = {};
    var countEdit = parseInt('{{$task->task->count()}}');
    var totalcountEdit = countEdit;
    var autocompletesWraps = [];
 editCount = 0;
$(document).ready(function(){
    // console.log(countEdit);
   // autocompletesWraps.push('add1');

    $('#selectize-optgroups').selectize();
    $('#selectize-optgroup').selectize();

    for (var i = 1; i <= countEdit; i++) {
        autocompletesWraps.push('add'+i);
        loadMap(autocompletesWraps);
    }
    loadMap(autocompletesWraps);
});
    $(document).ready(function() {
        $('.dropify').dropify();
        $(".shows").hide();
        $(".addspan").hide();
        $(".tagspan").hide();
        $(".tagspan2").hide();
        $(".searchspan").hide();
        $(".appoint").hide();
        $(".datenow").hide();
        $(".oldhide").hide();
        $(".pickup-barcode-error").hide();
        $(".drop-barcode-error").hide();
        $(".appointment-barcode-error").hide();
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
        // $("#file").click(function() {
        //     $('.imagepri').remove();
            
        // });
        
        $(document).on('click', ".span1", function() {
            var task_id = $(this).attr("data-taskid");
            if(task_id != undefined && task_id > 0){   
                $(this).closest(".copyin").remove();       ////// while update the task in dispatch 
                var status = TaskDeleteSingle(task_id);
               
            }else{
                $(this).closest(".copyin").remove();
            }
           
        });


        ///////////////////// ****************              delete single task ********************* //////////////////////////////

        function TaskDeleteSingle(task_id) {
    //alert(data);
    var CSRF_TOKEN = $("input[name=_token]").val();
    $.ajax({
        method: 'post',
        headers: {
            Accept: "application/json"
        },
        url: "{{route('tasks.single.destroy')}}",
        data: {_token: CSRF_TOKEN,task_id:task_id},
        success: function(response) {
            //alert(response)
            if (response) {
                if(response.count == 1)
                window.location.href = response.url;
                else
                return 1;
               // window.location.href = response.url;
            } else {
                return 2;
            }
            //return response;
        },
        error: function(response) {
            return 2;
        }
    });
}


        //////////////////// ************************** end delete single task ************************* ///////////////////////////
        
        //var a = 0;
        var a = totalcountEdit-1;
        var post_count = 1;
        $('#adds a').click(function() {
           
            countEdit = countEdit + 1;
            var abc = "{{ isset($maincount)?$maincount:0 }}";
            var newcount = $('#newcount').val();
            
            post_count = parseInt(newcount) + 1;
          
            a++;
           
                    var newids = null;
                    
                    var $div = $('div[class^="alTaskType copyin check-validation"]:last');
                    var newcheck = $div.find('.redio');
                    $.each(newcheck, function(index, elem){
                        var jElem = $(elem); // jQuery element
                        var name = jElem.prop('checked');
                        var id = jElem.prop('id');
                        if(name == true){
                          newids = id;
                        }
                    });
                   
                    var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
                    var $clone = $div.clone().prop('class', 'alTaskType copyin check-validation');
                    $clone.insertAfter('[class^="alTaskType copyin check-validation"]:last');
                    // get all the inputs inside the clone
                    var inputs = $clone.find('.redio');

                    $clone.find('.mainaddress').prop('id', 'add' + countEdit);
                    $clone.find('.cust1_add').prop('id', 'add' + countEdit +'-input');
                    $clone.find('.cust1_btn').prop('num', 'add' + countEdit);
                    $clone.find('.cust1_btn').prop('id', 'add' + countEdit);
                    $clone.find('.cust1_latitude').prop('id', 'add' + countEdit +'-latitude');
                    $clone.find('.cust1_longitude').prop('id', 'add' + countEdit +'-longitude');
                    // for each input change its name/id appending the num value
                    var count0 = 1;

                    $('#add'+countEdit+' input[type="text"]').val('');

                    $.each(inputs, function(index, elem){
                        var jElem = $(elem); // jQuery element
                        var name = jElem.prop('name');
                       // alert(name);
                        // remove the number
                        name = name.replace(/\d+/g, '');
                        name += a;
                       // alert(name);
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

                        var taskid = jElem.attr("data-taskid");
                        jElem.attr("data-taskid", 0);
                    });

                    var address1 = $clone.find('.address');
                    $.each(address1, function(index, elem){
                           
                        var jElem = $(elem); // jQuery element
                        jElem.prop('required', true);
                    });

                    var flat_no = $clone.find('.flat_no');
                    $.each(flat_no, function(index, elem){
                        var jElem = $(elem)
                        var name = jElem.prop('id');
                        // console.log(name);
                        name = name.replace(/\d+/g, '');
                        name = 'add'+post_count+'-flat_no';
                        jElem.prop('id', name);
                    });

                    var alcoholicItem = $clone.find('.alcoholic_item');
                    $.each(alcoholicItem, function(index, elem){
                        var jElem = $(elem)
                        var name = jElem.prop('id');
                        name = name.replace(/\d+/g, '');
                        name = 'add'+post_count+'-alcoholic_item';
                        jElem.prop('id', name);

                        var alcoholicItemLabel = $clone.find('.alcoholic_item_label');
                        $.each(alcoholicItemLabel, function(index, elem){
                            var jElem = $(elem);
                            var labelName = jElem.prop('for');
                            labelName = labelName.replace(/\d+/g, '');
                            labelName = 'add'+post_count+'-alcoholic_item';
                            jElem.prop('for', labelName);
                        });
                    });


                    var postcode1 = $clone.find('.postcode');
                    $.each(postcode1, function(index, elem){
                        var jElem = $(elem)
                        var name = jElem.prop('id');
                        // console.log(name);
                        name = name.replace(/\d+/g, '');
                        name = 'add'+post_count+'-postcode';
                        jElem.prop('id', name);
                        //   var jElem = $(elem); // jQuery element
                        //jElem.prop('required', true);
                        post_count++;
                        // console.log(post_count);
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

        $(document).on("click", ".submitUpdateTaskHeader", function(e) {
            e.preventDefault();
            var err = 0;
            
            // $("input[name='short_name[]']").each(function(){
            //     var shortName = $(this).val();
            //     if(shortName == ''){
            //         err = 1;
            //         $(this).closest('.check-validation').find('.addspan').show();
            //         return false;
            //     }
            // });
            
            $("input[name='address[]']").each(function(){
                var address = $(this).val();
                if(address == ''){
                    err = 1;
                    $(this).closest('.check-validation').find('.addspan').show();
                    return false;
                }
            });

            // $(".selecttype").each(function(){
            //     var taskselect              = $(this).val();
            //     var checkPickupBarcode      = $('#check-pickup-barcode').val();
            //     var checkDropBarcode        = $('#check-drop-barcode').val();
            //     var checkAppointmentBarcode = $('#check-appointment-barcode').val();
            //     var barcode                 = $(this).closest('.check-validation').find('.barcode').val();
            //     if(taskselect == 1 && checkPickupBarcode == 1 && barcode == ''){
            //         $(this).closest('.check-validation').find('.pickup-barcode-error').show();
            //         err = 1;
            //         return false;
            //     }else if(taskselect == 2 && checkDropBarcode == 1 && barcode == ''){
            //         $(this).closest('.check-validation').find('.drop-barcode-error').show();
            //         err = 1;
            //         return false;
            //     }else if(taskselect == 3 && checkAppointmentBarcode == 1 && barcode == ''){
            //         $(this).closest('.check-validation').find('.appointment-barcode-error').show();
            //         err = 1;
            //         return false;
            //     }
            // });

            if(err == 1){
                return false;
            }else if(err == 0){
                $(".pickup-barcode-error").hide();
                $(".drop-barcode-error").hide();
                $(".appointment-barcode-error").hide();
                var id       = $("#order-id").val();
                var formData = new FormData(document.querySelector("#taskFormHeader"));
                // for (var [key, value] of formData.entries()) { 
                //     console.log(key, value);
                // }
                // console.log(formData);
                updateTaskSubmit(formData, 'POST', '/updatetasks/tasks/'+id);
            }
        });

        function updateTaskSubmit(data, method, url) {
            for(var i=0; i < savedFileListArray.length; i++){
                data.append('savedFiles[]', savedFileListArray[i]);
            }
            $.ajax({
                method: method,
                headers: {
                    Accept: "application/json"
                },
                url: url,
                data: data,
                contentType: false,
                processData: false,
                success: function(response) {
                    window.location.href = '/tasks';
                },
                error: function(response) {
                    
                }
            });
        }

        //$("#myselect").val();
        $(document).on('change', ".selecttype", function() {
        
            
            if (this.value == 3){
               $span = $(this).closest(".firstclone1").find(".appoint").show();
            //    console.log($span); 
            }   
            else{
                $(this).closest(".firstclone1").find(".appoint").hide();
            
            }
            
        });

        $(".callradio input").click(function() { 

            if ($(this).is(":checked")) { 
              $span = $(this).closest(".requried").find(".alladdress");
            //   console.log($span);
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
        $("input[type='radio'].assignRadio").click(function() {
            var radioValue = $("#rediodiv input[type='radio']:checked").val();
            if (radioValue == 'a') {
                $(".tags").show();
                $(".drivers").hide();
            }
            if (radioValue == 'u') {
                $(".tags").hide();
                $(".drivers").hide();
            }
            if (radioValue == 'm') {
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
          $(".drivers").show();
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

        $(document).on('click', '#clear-address', function(){
            $(this).closest('.check-validation').find("input:checked").prop('checked', false);
            $(this).closest('.check-validation').find("input[name='short_name[]']").val('');
            $(this).closest('.check-validation').find("input[name='address_email[]']").val('');
            $(this).closest('.check-validation').find("input[name='address[]']").val('');
            $(this).closest('.check-validation').find("input[name='address_phone_number[]']").val('');
            $(this).closest('.check-validation').find("input[name='post_code[]']").val('');
            $(this).closest('.check-validation').find("input[name='flat_no[]']").val('');
            $(this).closest('.check-validation').find("input[name='latitude[]']").val('');
            $(this).closest('.check-validation').find("input[name='longitude[]']").val('');
        });
        
        $('input[type="radio"]').each(function(){
            if($(this).is(':checked')) {
                var shortName = $(this).data("srtadd");
                if(shortName != undefined){
                    var address     = $(this).data("adr");
                    var latitude    = $(this).data("lat");
                    var longitude   = $(this).data("long");
                    var postCode    = $(this).data("pstcd");
                    var flat_no     = $(this).data("flat_no");
                    var email       = $(this).data("emil");
                    var phoneNumber = $(this).data("ph");
                    $(this).closest('.check-validation').find("input[name='short_name[]']").val(shortName);
                    $(this).closest('.check-validation').find("input[name='address_email[]']").val(email);
                    $(this).closest('.check-validation').find("input[name='address[]']").val(address);
                    $(this).closest('.check-validation').find("input[name='address_phone_number[]']").val(phoneNumber);
                    $(this).closest('.check-validation').find("input[name='post_code[]']").val(postCode);
                    $(this).closest('.check-validation').find("input[name='flat_no[]']").val(flat_no);
                    $(this).closest('.check-validation').find("input[name='latitude[]']").val(latitude);
                    $(this).closest('.check-validation').find("input[name='longitude[]']").val(longitude);
                }
            }
        });

        $(document).on('click', '.old-select-address', function(){
            var shortName   = $(this).data("srtadd");
            var address     = $(this).data("adr");
            var latitude    = $(this).data("lat");
            var longitude   = $(this).data("long");
            var postCode    = $(this).data("pstcd");
            var email       = $(this).data("emil");
            var phoneNumber = $(this).data("ph");
            var flat_no     = $(this).data("flat_no");
            
            $(this).closest('.check-validation').find("input[name='short_name[]']").val(shortName);
            $(this).closest('.check-validation').find("input[name='address_email[]']").val(email);
            $(this).closest('.check-validation').find("input[name='address[]']").val(address);
            $(this).closest('.check-validation').find("input[name='address_phone_number[]']").val(phoneNumber);
            $(this).closest('.check-validation').find("input[name='post_code[]']").val(postCode);
            $(this).closest('.check-validation').find("input[name='flat_no[]']").val(flat_no);
            $(this).closest('.check-validation').find("input[name='latitude[]']").val(latitude);
            $(this).closest('.check-validation').find("input[name='longitude[]']").val(longitude);
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
                    var customerdata = data.customer;
                    var array = data.location;
                    if(customerdata.dial_code!=null)
                    {
                        $(".searchshow").find("input[name='phone_existing']").val("+"+customerdata.dial_code+""+customerdata.phone_number);
                    }else{
                        $(".searchshow").find("input[name='phone_existing']").val(customerdata.phone_number);
                    }
                    $(".searchshow").find("input[name='email_existing']").val(customerdata.email);
                    $('.withradio .append').remove();
                    jQuery.each(array, function(i, val) {
                        $(".withradio").append(
                          '<div class="append"><div class="custom-control custom-radio count"><input type="radio" id="' + val.id + '" name="old_address_id" value="' + val.id + '" class="custom-control-input redio old-select-address callradio" data-srtadd="'+ val.short_name +'" data-adr="'+ val.address +'" data-lat="'+ val.latitude +'" data-long="'+ val.longitude +'" data-pstcd="'+ val.post_code +'" data-emil="'+ val.email +'" data-ph="'+ val.phone_number +'"><label class="custom-control-label" for="' + val.id + '"><span class="spanbold">' + val.short_name +
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
                    $(".searchspan").hide();
                } else {
                    $(".searchspan").show();
                    return false;
                }
            }

            var selectedVal = "";
            var selected = $("#typeInputss input[type='radio']:checked");
            selectedVal = selected.val();
            // console.log(selectedVal);
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

        // var inputLocalFont = document.getElementById("file");
        //  inputLocalFont.addEventListener("change",previewImages,false);

        //  function previewImages(){
        //   var fileList = this.files;

        //   var anyWindow = window.URL || window.webkitURL;

        //   for(var i = 0; i < fileList.length; i++){
        //    var objectUrl = anyWindow.createObjectURL(fileList[i]);
        //    $('#imagePreview').append('<img src="' + objectUrl + '" class="imagepri" />');
        //    window.URL.revokeObjectURL(fileList[i]);
        //    }


        // }


    });

function loadMap(autocompletesWraps){

    // console.log(autocompletesWraps);
    $.each(autocompletesWraps, function(index, name) {
        const geocoder = new google.maps.Geocoder; 

        if($('#'+name).length == 0) {
            return;
        }
        //autocomplete[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); console.log('hello');
        autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name+'-input'), { types: ['geocode'] });
        google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
            
            var place = autocomplete[name].getPlace();
            
            // console.log('autocomplete[name]', autocomplete[name]);
            geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                
                if (status === google.maps.GeocoderStatus.OK) {

                    
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    
                    document.getElementById(name + '-latitude').value = lat;
                    document.getElementById(name + '-longitude').value = lng;
                    const postCode = results[0].address_components.find(addr => addr.types[0] === "postal_code").short_name;
                    document.getElementById(name + '-postcode').value = postCode;
                }
            });
        });
    });

}

$(document).on('click', '.showMapTask', function(){
    var no = $(this).attr('id') ??  $(this).attr('num') ;
    // console.log(no);
    var lats = document.getElementById(no+'-latitude').value;
    var lngs = document.getElementById(no+'-longitude').value;
    var address = document.getElementById(no+'-input').value;
    document.getElementById('map_for').value = no;

    if(lats == null || lats == '0'){
        lats = 51.508742;
    }
    if(lngs == null || lngs == '0'){
        lngs = -0.120850;
    }
    if(address==null){
            address= '';
    }
    var infowindow = new google.maps.InfoWindow();
    var geocoder = new google.maps.Geocoder();

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
        document.getElementById('addredd_map').value= address ; 
        // marker drag event
        {{-- google.maps.event.addListener(marker,'drag',function(event) {
            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
        }); --}}
        google.maps.event.addListener(marker, 'dragend', function() {
                    geocoder.geocode({
                    'latLng': marker.getPosition()
                    }, function(results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                             document.getElementById('lat_map_header').value = marker.getPosition().lat();
                             document.getElementById('lng_map_header').value = marker.getPosition().lng();
                             document.getElementById('addredd_map').value= results[0].formatted_address; 
                        
                            infowindow.setContent(results[0].formatted_address);
                         
                            infowindow.open(map, marker);
                        }
                    }
                    });
                });

        //marker drag event end
        {{-- google.maps.event.addListener(marker,'dragend',function(event) {
            var zx =JSON.stringify(event);
            // console.log(zx);


            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
            //alert("lat=>"+event.latLng.lat());
            //alert("long=>"+event.latLng.lng());
        }); --}}
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
    var addredd_map = document.getElementById('addredd_map').value;
    // console.log(mapLat+'-'+mapLlng+'-'+mapFor);
    document.getElementById(mapFor + '-latitude').value = mapLat;
    document.getElementById(mapFor + '-longitude').value = mapLlng;
    document.getElementById(mapFor + '-input').value = addredd_map;


    $('#show-map-modal').modal('hide');
});

$('.onlynumber').keyup(function ()
    { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});



$(document).on('click', '.mdi-delete-single-task', function() {            
            var r = confirm("{{__('Are you sure?')}}");
            // console.log($(this).attr('taskid'));
            if (r == true) {
               var taskid = $(this).attr('taskid');
               $('form#taskdeletesingle'+taskid).submit();

            }
        });


</script>