<script type="text/javascript">
$( document ).ready(function() {
    initDataTable();
    
    function initDataTable() {
        $('#agent-listing').DataTable({
            "dom": '<"toolbar">Bfrtip',
            "scrollX": true,
            "destroy": true,
            "serverSide": true,
            "responsive": true,
            "processing": true,
            "iDisplayLength": 10,
            language: {
                        search: "",
                        paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                        searchPlaceholder: "Search Agent",
                        'loadingRecords': '&nbsp;',
                        'processing': '<div class="spinner"></div>'
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
            buttons: [{   
                className:'btn btn-success waves-effect waves-light',
                text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>Export CSV',
                action: function ( e, dt, node, config ) {
                    window.location.href = "{{ route('agents.export') }}";
                }
            }],
            ajax: {
                url: "{{url('agent/filter')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.imgproxyurl = '{{$imgproxyurl}}';
                }
            },
            columns: [
                {data: 'uid', name: 'uid', orderable: true, searchable: false},
                {data: 'profile_picture', name: 'profile_picture', orderable: true, searchable: false, "mRender": function ( data, type, full ) {
                    return '<img alt="'+full.id+'" src="'+full.profile_picture+'" width="40">';
                }},
                {data: 'name', name: 'name', orderable: true, searchable: false, "mRender": function ( data, type, full ) {
                    return '<a href="javascript:void(0);" class="text-body font-weight-semibold">'+full.name+'</a>';
                }},
                {data: 'phone_number', name: 'phone_number', orderable: true, searchable: false},
                {data: 'type', name: 'type', orderable: true, searchable: false},
                {data: 'team', name: 'team', orderable: true, searchable: false},
                {data: 'vehicle_type_id', name: 'vehicle_type_id', orderable: true, searchable: false, "mRender": function ( data, type, full ) {
                    return '<img alt="" style="width: 80px;" src="'+full.vehicle_type_id+'">';
                }},
                {data: 'cash_to_be_collected', name: 'cash_to_be_collected', orderable: true, searchable: false},
                {data: 'driver_cost', name: 'driver_cost', orderable: true, searchable: false},
                {data: 'cr', name: 'cr', orderable: true, searchable: false},
                {data: 'dr', name: 'dr', orderable: true, searchable: false},
                {data: 'pay_to_driver', name: 'pay_to_driver', orderable: true, searchable: false},
                {data: 'is_approved', name: 'is_approved', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                    var check = (full.is_approved == 1)? 'checked' : '';
                    return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input agent_approval_switch" '+check+' id="customSwitch_'+full.id+'" data-id="'+full.id+'"><label class="custom-control-label" for="customSwitch_'+full.id+'"></label></div>';
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });         
              
    }

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
    $(document).on("change","#add-agent-modal .xyz",function(e){
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

    $(document).on("click",".openModal",function(e){
        $('#add-agent-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        makeTag();

        phoneInput();
    });

    jQuery('#onfoot').click();

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
    $(document).on("click",".editIcon",function(e){
        e.preventDefault();
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

    $(document).on("click",".viewIcon",function(e){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uid = $(this).attr('agentId');

        $.ajax({
            type: "get",
            url: "<?php echo url('agent'); ?>" + '/' + uid + '/show',
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#view-agent-modal #viewCardBox').html(data.html);
                $('#view-agent-modal').modal({
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
                var imgs = $('#file').attr('showImg');

                $('#file').attr("data-default-file", imgs);
                $('#file').dropify();
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
    $(document).on("submit","#submitAgent",function(e){
        e.preventDefault();
        // $(document).on('click', '.submitAgentForm', function() { 
        var form = document.getElementById('submitAgent');
        var formData = new FormData(form);
        var urls = "{{URL::route('agent.store')}}";
        saveTeam(urls, formData, inp = '', modal = 'add-agent-modal');
    });

    /* edit Team using ajax*/
    $(document).on("submit","#edit-agent-modal #UpdateAgent",function(e){
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
    $(document).on("click",".submitpayreceive",function(e){
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

    $(document).on("change",".agent_approval_switch",function(e){
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
                if (data.status == 1) {
                    $.NotificationApp.send("", data.message, "top-right", "#5ba035", "success");
                }
            }
        });
    });
});
</script>