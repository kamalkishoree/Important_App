<script type="text/javascript">
    var tagList = "{{$showTag}}";
    tagList = tagList.split(',');

    function makeTag() {
        $('.myTag1').tagsInput({
            'autocomplete': {
                source: tagList
            }
        });
    }
    var mobile_number = '';

    // $('#add-agent-modal .xyz').val(mobile_number.getSelectedCountryData().dialCode); 
    $('#add-agent-modal .xyz').change(function() {
        var phonevalue = $('.xyz').val();
        $("#countryCode").val(mobile_number.getSelectedCountryData().dialCode);
    });

    function phoneInput() {
        console.log('phone working');
        var input = document.querySelector(".xyz");

        var mobile_number_input = document.querySelector(".xyz");
        mobile_number = window.intlTelInput(mobile_number_input, {
            separateDialCode: true,
            hiddenInput: "full_number",
            initialCountry: '{{$selectedCountryCode}}',
            utilsScript: "{{ asset('telinput/js/utils.js') }}",
        });

    }

    $('.openModal').click(function() {
        $('#add-agent-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        makeTag();
             
        phoneInput();
    });

    $(document).ready(function() {
        jQuery('#onfoot').click();
    });

    $(document).on('click', '.click', function() { //alert('a');
        var radi = $(this).find('input[type="radio"]');
        radi.prop('checked', true);
        var check = radi.val();
        var act = radi.attr('act');
        switch (check) {
            case "1":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk_blue.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                break;
            case "2":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle_blue.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                break;
            case "3":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike_blue.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                break;
            case "4":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car_blue.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                break;
            case "5":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck_blue.png') }}");
                break;
        }
    });

    /* Get agent by ajax */
    $(".editIcon").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uid = $(this).attr('agentId');

        $.ajax({
            type: "get",
            url: "<?php echo url('agent'); ?>" + '/' + uid + '/edit',
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#edit-agent-modal #editCardBox').html(data.html);
                $('#edit-agent-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                makeTag();
                phoneInput();
                //$('.dropify').dropify();
                var imgs = $('#profilePic').attr('showImg');

                $('#profilePic').attr("data-default-file", imgs);
                $('#profilePic').dropify();
                $('').dropify();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });

    /* add Team using ajax*/
    // $("#add-agent-modal #submitAgent").submit(function(e) {

    // });
    $("#submitAgent").submit(function(e) {
        e.preventDefault();
        // $(document).on('click', '.submitAgentForm', function() { 
        var form = document.getElementById('submitAgent');
        var formData = new FormData(form);
        var urls = "{{URL::route('agent.store')}}";
        saveTeam(urls, formData, inp = '', modal = 'add-agent-modal');
    });

    /* edit Team using ajax*/
    $("#edit-agent-modal #UpdateAgent").submit(function(e) {
        e.preventDefault();
    });

    $(document).on('click', '.submitEditForm', function() {
        var form = document.getElementById('UpdateAgent');
        var formData = new FormData(form);
        var urls = document.getElementById('agent_id').getAttribute('url');
        saveTeam(urls, formData, inp = 'Edit', modal = 'edit-agent-modal');
        console.log(urls);
    });

    function saveTeam(urls, formData, inp = '', modal = '') {

        $.ajax({
            method: 'post',
            headers: {
                Accept: "application/json"
            },
            url: urls,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 'success') {
                    $("#" + modal + " .close").click();
                    location.reload();
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input" + inp + " input").addClass("is-invalid");
                        $("#" + key + "Input" + inp + " span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });

    }


    /* Get agent by ajax */
    $(".submitpayreceive").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();


        $.ajax({
            type: "post",
            url: "",
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#edit-agent-modal #editCardBox').html(data.html);
                $('#edit-agent-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                makeTag();
                //$('.dropify').dropify();
                var imgs = $('#profilePic').attr('showImg');

                $('#profilePic').attr("data-default-file", imgs);
                $('#profilePic').dropify();
                $('').dropify();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });

    $('.agent_approval_switch').on('change',function() {
        var is_approved = $(this).prop('checked') == true ? 1 : 0;
        var agent_id = $(this).data('id');

        $.ajax({
            type: "Post",
            dataType: "json",
            url: "{{ route('agent/approval_status')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                'is_approved': is_approved,
                'id': agent_id
            },
            success: function(data) {
                if(data.status == 1){
                    $.NotificationApp.send("",data.message,"top-right","#5ba035","success"); 
                }
            }
        });
    })
</script>