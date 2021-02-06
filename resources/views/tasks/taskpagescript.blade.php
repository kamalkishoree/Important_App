<script>
    $(document).ready(function() {
        $('#agents-datatable').DataTable();
    });

    function handleClick(myRadio) {
        $('#getTask').submit();
    }

    //this is for task detail pop-up

    $(document).on('click', '.showtasks', function() {
        var CSRF_TOKEN = $("input[name=_token]").val();
        var tour_id = $(this).val();
        var basic = window.location.origin;
        var url = basic + "/tasks/list/" + tour_id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
                _token: CSRF_TOKEN,
                status: status
            },
            success: function(data) {
                console.log(data.task);
                // console.log(data[0].task);
                //abc = $('#removedata').html('');
                //console.log(abc);
                // $('#removedata').hide();
                $('.repet').remove();
                var taskname = '';
                $.each(data.task, function(index, elem) {


                    switch (elem.task_type_id) {
                        case 1:
                            taskname = 'Pickup task';
                            break;
                        case 2:
                            taskname = 'Drop Off task';
                            break;
                        case 3:
                            taskname = 'Appointment';
                            break;
                    }
                    var date = new Date(elem.order_time);
                    var options = {
                        hour12: true
                    };
                    $(document).find('.allin').before(
                        '<div class="repet"><div class="task-card p-3"><div class="p-2 assigned-block"><h5>' +
                        taskname +
                        '</h5><div class="wd-10"><img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}"></div><div class="wd-90"><h6>' +
                        elem.location.address + '</h6><span>' + elem.location
                        .short_name +
                        '</span><h5 class="mb-1"><span></span></h5><div class="row"><div class="col-md-6"></div><div class="col-md-6 text-right"><button class="assigned-btn">' +
                        data.status + '</button></div></div></div></div></div></div>');


                });

                $('#task-list-modal').modal('show');

            }

        });
    });

    //this is for accounting calculation  pop-up

    $(document).on('click', '.showaccounting', function() {
        // $('#task-accounting-modal').modal('show');
        //   return;
        var CSRF_TOKEN = $("input[name=_token]").val();
        var tour_id = $(this).val();
        var basic = window.location.origin;

        var url = basic + "/tasks/list/" + tour_id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
                _token: CSRF_TOKEN,
            },
            success: function(data) {

                $("#base_distance").text(round(data.base_distance));
                $("#actual_distance").text(data.actual_distance);
                $("#billing_distance").text(round(data.actual_distance - data.base_distance, 2));
                var sendDistance = (data.actual_distance - data.base_distance) * data.distance_fee;
                $("#distance_cost").text(round(sendDistance, 2));

                $("#base_duration").text(data.base_duration);
                $("#actual_duration").text(data.actual_time);
                $("#billing_duration").text(data.actual_time - data.base_duration);
                var sendDuration = (data.actual_time - data.base_duration) * data.duration_price;
                $("#duration_cost").text(sendDuration);

                $("#base_price").text(data.base_price);
                $("#duration_price").text(data.duration_price + ' (Per min)');
                $("#distance_fee").text(data.distance_fee + ' (' + data.distance_type + ')');
                $("#driver_type").text(data.driver_type);

                $("#order_cost").text(data.order_cost);
                $("#driver_cost").text(data.driver_cost != 0.00 ? data.driver_cost :
                    'Not assigned yet');

                $("#base_waiting").val(data.base_waiting);
                $("#distance_fee").val(data.distance_fee);
                $("#cancel_fee").val(data.cancel_fee);
                $("#agent_commission_percentage").text(data.agent_commission_percentage);
                $("#agent_commission_fixed").text(data.agent_commission_fixed);
                $("#freelancer_commission_percentage").text(data.freelancer_commission_percentage);
                $("#freelancer_commission_fixed").text(data.freelancer_commission_fixed);





                $('#task-accounting-modal').modal('show');

            }

        });
    });

    function round(value, exp) {
        if (typeof exp === 'undefined' || +exp === 0)
            return Math.round(value);

        value = +value;
        exp = +exp;

        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
            return NaN;

        // Shift
        value = value.toString().split('e');
        value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    }

</script>

