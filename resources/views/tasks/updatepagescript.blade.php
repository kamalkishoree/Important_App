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

                    $clone.find('.cust1_add_div').prop('id', 'add' + countEdit);
                    $clone.find('.cust1_add').prop('id', 'add' + countEdit +'-input');
                    $clone.find('.cust1_btn').prop('num', 'add' + countEdit);
                    $clone.find('.cust1_btn').prop('id', 'add' + countEdit);
                    $clone.find('.cust1_latitude').prop('id', 'add' + countEdit +'-latitude');
                    $clone.find('.cust1_longitude').prop('id', 'add' + countEdit +'-longitude');
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
    var no = $(this).attr('id');
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