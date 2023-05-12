$(function () {

    dispatcherStorage.removeStorageAll();
    dispatcherStorage.setStorageSingle('eventType', 'attendence');
    var product_id = vendor_id = title = block = appoin = agent_id = calendar = Is_gerenal='';
    var CSRF_TOKEN = $("input[name=_token]").val();
    var calendarEl = document.getElementById('calendar');
    $(document).on('click', '.agent_slot_button', function () {
        agent_id = $(this).data('agent_id');
        spinnerJS.showSpinner();
        $('#agentTablePopup').modal('show');
        $('#agentTablePopup').find('.nav button:first').tab('show');
        fullCalendarInt(agent_id);
    });

   
    $(document).on('change', '.slotTypeEdit', function () {
        var val = $(this).val();
        dispatcherStorage.setStorageSingle('SlotType', val);
        if (val == 'date') {
            if ($('.forDate').length > 0) {
                $(".forDate").fadeIn(1000);
            } else {
                $(".forDateEdit").fadeIn(1000);
            }

        } else {
            if ($('.forDate').length > 0) {
                $(".forDate").fadeOut(500);
            } else {
                $(".forDateEdit").fadeOut(500);
            }

        }
    })

    $(document).on('change', '#edit_slot_date', function () {
        var edit_slot_date = $(this).val();
        dispatcherStorage.setStorageSingle('EditSlotDate', edit_slot_date);
    });

    $(document).on('change', '#recurring', function (e) {
        var edit = $("#recurring").hasClass("edit_recurring") ? 1 : 0;
        if (e.target.checked) {
            dispatcherStorage.setStorageSingle('recurring_val', 1)
            $(".weekDays").fadeIn(1000);
            if (edit == 1) {
                document.getElementById("blocktime").disabled = false;
                $(".forDate").fadeOut(1000);
            }

        } else {
            dispatcherStorage.setStorageSingle('recurring_val', 0)
            $(".weekDays").fadeOut(1000);
            if (edit == 1) {
                $(".forDate").fadeIn(1000);
                document.getElementById("blocktime").disabled = true;
            }
        }

        dispatcherStorage.setStorageSingle('recurring', e.target.checked);
    });


    $(document).on('click', '#deleteSlotBtn', function () {
        var slot_date = $('#edit_slot_date').val();
        var slot_id = $('#edit_slot_id').val();
        var recurring = dispatcherStorage.getStorage('recurring_val')
        var blocktime = $('#blocktime').val();
        var week_day = [];

        //const slotType = dispatcherStorage.getStorage('SlotType')

        $.each($("input:checkbox[name='week_day[]']:checked"), function () {
            week_day.push($(this).val());
        });
        var formData = {
            slot_date: slot_date,
            slot_id: slot_id,
            week_day: week_day,
            blocktime: blocktime,
            recurring: recurring,
            agent_id: agent_id,
        }
        //  console.log(formData);
        //return false;
        // $('#edit-slot-modal #deleteSlotDate').val(date);

        Swal.fire({
            title: 'Are you sure? You want to delete slot.',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete all!',
            cancelButtonText: 'Yes, delete single day!',
            reverseButtons: true,
            preConfirm: () => {

            }, onOpen: function () {
            }
        }).then(async (result) => {


            if (result.isConfirmed) {
                formData.delete_type = 'all'
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                formData.delete_type = 'single'
            }
            console.log(formData);
            await deleteSlot(formData)
        })

        return false;
    })

    $(document).on('click', '.get_attendence', function () {
        var event = $(this).attr('data-eventType');
        dispatcherStorage.setStorageSingle('eventType', event);
        spinnerJS.showSpinner();
        if ($('#calendar').length > 0) {
            if (calendar) {
                calendar.destroy();
            }}
        fullCalendarInt(agent_id, event);
    });

    async function fullCalendarInt(agent_id, eventType = 'attendence') {

        var eventEnabled = true;
        if (eventType == 'attendence') {
            eventEnabled = false;
        } else if (eventType == 'new_booking') {
            eventEnabled = true;
        } else {
            eventEnabled = true;
        }
        if ($('#calendar').length > 0) {
            if (calendar) {
                calendar.destroy();
            }

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                //disableDragging: true,
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                  //  right: 'timeGridWeek,timeGridDay'
                },
                slotLabelFormat: [
                    {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: ""
                    }
                ],
                eventTimeFormat: { // like '14:30:00'
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: ""
                },
                navLinks: true,
                selectable: eventEnabled,
                selectMirror: true,
                selectOverlap: false,
                height: 'auto',
                editable: false,
                nowIndicator: true,
                 eventRender: function(info) {
      info.el.querySelector('.fc-event-title').innerHTML = "<i>" + info.event.title + "</i>";
    },
                //eventMaxStack: 1,
                select: function (arg) {

                    dispatcherStorage.removeStorageSingle('recurring');
                    console.log(arg);
                    initDatetimeRangePicker(arg.startStr, arg.endStr);
                    Swal.fire({
                        title: 'Add working hours',
                        html: AddSlotHtml,
                        confirmButtonText: 'Submit',
                        focusConfirm: false,
                        customClass: "edit-slot-agent",
                        preConfirm: () => {
                            const start_time = Swal.getPopup().querySelector('#start_time').value
                            const end_time = Swal.getPopup().querySelector('#end_time').value
                            const blocktime = Swal.getPopup().querySelector('#blocktime').value
                            const recurring = Swal.getPopup().querySelector('#recurring').value //dispatcherStorage.getStorage('recurring_val');
                            const memo = Swal.getPopup().querySelector('#memo').value;
                            const booking_type = Swal.getPopup().querySelector('#booking_type').value;
                            const methods = Swal.getPopup().querySelector('.methods').value;
                           
                            var week_day = [];

                            //const slotType = dispatcherStorage.getStorage('SlotType')

                            $.each($("input:checkbox[name='week_day[]']:checked"), function () {
                                week_day.push($(this).val());
                            });

                            if (start_time == '' && end_time == '' && blocktime == '' && memo == '' && booking_type == '') {
                                Swal.showValidationMessage(`All feilds are required!!`)
                                return false;
                            }
                            if (recurring == 'true') {
                                console.log(week_day);
                                if (!week_day.length > 0) {
                                    Swal.showValidationMessage(`Please select days to recurring!!`)
                                    return false;
                                }

                            }
                            return { start_time: start_time, end_time: end_time, week_day: week_day, blocktime: blocktime, recurring: recurring, booking_type: booking_type, memo: memo,methods:methods }
                        }, onOpen: function () {


                        }
                    }).then(async (result) => {

                        if (result.dismiss == undefined) {
                            var formData = {
                                start_time: result.value.start_time,
                                end_time: result.value.end_time,
                                week_day: result.value.week_day,
                                blocktime: result.value.blocktime,
                                recurring: result.value.recurring,
                                agent_id: agent_id,
                                booking_type: result.value.booking_type,
                                memo: result.value.memo,
                                method:result.value.methods
                            }
                            console.log(formData);
                            await add_slot_time(formData)
                        } else {
                            //alert()
                            //arg.remove()
                        }


                    })


                    // calendar.addEvent({
                    //     title: '',
                    //     start: arg.start,
                    //     end: arg.end,
                    //     allDay: arg.allDay
                    // })
                    $('#standard-modal').modal({
                        //backdrop: 'static',
                        keyboard: false
                    });
                    var day = arg.start.getDay() + 1;
                    $('#day_' + day).prop('checked', true);
                    console.log(arg.start.getHours());

                    initDatetimeRangePicker(new Date(arg.start), new Date(arg.end));
                    if (arg.allDay == true) {
                        document.getElementById('start_time').value = "00:00";
                        document.getElementById('end_time').value = "23:59";
                    } else {
                        var startTime = ("0" + arg.start.getHours()).slice(-2) + ":" + ("0" + arg.start.getMinutes()).slice(-2);
                        var EndTime = ("0" + arg.end.getHours()).slice(-2) + ":" + ("0" + arg.end.getMinutes()).slice(-2);

                        document.getElementById('start_time').value = startTime;
                        document.getElementById('end_time').value = EndTime;
                    }
                },

eventContent: function(arg) {
  let italicEl = document.createElement('p')
  italicEl.className = "p-align";

    italicEl.innerHTML = arg.event.title
  let arrayOfDomNodes = [ italicEl ]
  return { domNodes: arrayOfDomNodes }
},
                events: function (info, successCallback, failureCallback) {
                    var calender_data_url = AgentAttendence_calender_url.replace(":id", agent_id)
                    $.ajax({
                        url: calender_data_url,
                        type: "GET",
                        data: `start=${info.startStr}&end=${info.endStr}&eventType=${eventType}`,
                        dataType: 'json',
                        success: function (response) {
                            var startDate = moment(info.start).format('MMM DD');
                            var endDate = moment(info.end - 1).format('DD, YYYY');
                            $("#calendar_slot_alldays_table thead th").html(startDate + " - " + endDate);
                            $("#calendar_slot_alldays_table tbody").html("");
                            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            var slotDayList = [];
                            var events = [];
                            $("#duration").css("display","block");
                            $("#duration").html("Total Hours: "+response.duration);
                            $.each(response.data, function (index, data) {
                                var slotDay = parseInt(moment(data.start).format('d')) + 1;
                                var slotStartTime = moment(data.start).format('h:mm A');
                                 var slotEndTime = moment(data.end).format('h:mm A');
                                if(data.end_time == ''){
									 var slotEndTime = 'N/A';
								}
                                $.each(days, function (key, value) {
                                    if (slotDay == key + 1) {
                                        if (slotDayList.includes(slotDay)) {
                                            $("#calendar_slot_alldays_table tbody tr[data-slotDay='" + slotDay + "'] td:nth-child(2)").append("<br>" + slotStartTime + " - " + slotEndTime);
                                        }
                                        else {
                                            $("#calendar_slot_alldays_table tbody").append("<tr data-slotDay=" + slotDay + "><td>" + value + "</td><td>" + slotStartTime + " - " + slotEndTime + "</td></tr>");
                                        }
                                    }
                                });
                                slotDayList.push(slotDay);
                                events.push({
                                    title: data.title,
                                    start: data.start,
                                    end: data.end,
                                    duration:data.duration,
                                     in_time:data.in_time,
                                      out_time:data.out_time,
                                    type: data.type,
                                    color: data.color,
                                    type_id: data.type_id,
                                    slot_id: data.slot_id,
                                    schedule_date: data.memo,
                                    memo: data.memo,
                                    booking_type: data.booking_type,
                                    schedule_date: data.memo,
                                    start_time: data.start_time,
                                    end_time: data.end_time,
                                    start_date: data.start_date,
                                    end_date: data.end_date,
                                    agent_id: agent_id,
                                    recurring: data.recurring,
                                    order_url: data.order_url,
                                    days: data.days
                                    // slot_delivery: data.slot_delivery,
                                    // service_area: data.service_area,
                                });
                            });
                            successCallback(events);
                        }
                    });
                },
                eventResize: function (arg) {
                }
            });
            setTimeout(async () => {
                await calendar.render();
                spinnerJS.hideSpinner()
            }, 200)


        }

    }
    async function add_slot_time(formData, action = 'add') {
        var evttype = dispatcherStorage.getStorage('eventType');
        var actionUrl = (action == 'add') ? 'add_slot' : 'update_slot';
   
        if (formData.method == "services") {
            url = "general";
        } else {
            url = "agent"
        }
        axios.post(`${url}/${actionUrl}`, formData)
            .then(async response => {
                console.log(response);
                if (response.data.status == "Success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                        //footer: '<a href="">Why do I have this issue?</a>'
                    })
                    fullCalendarInt(agent_id, evttype)

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops',
                        text: response.data.message,
                        //footer: '<a href="">Why do I have this issue?</a>'
                    })
                }
            })
            .catch(e => {
                console.log(e);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong, try again later!',
                })
            })
    }

    async function deleteSlot(formData) {

        axios.post(`agent/slot/delete`, formData)
            .then(async response => {
                console.log(response.data.status);
                if (response.data.status == "Success") {

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                        //footer: '<a href="">Why do I have this issue?</a>'
                    })
                    fullCalendarInt(agent_id)
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops',
                        text: response.data.message,
                        //footer: '<a href="">Why do I have this issue?</a>'
                    })
                }
            })
            .catch(e => {
                console.log(e);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong, try again later!',
                })
            })
    }


    function initDatetimeRangePicker(start_date = "", end_date = "") {
        //$('#blocktime').val(start_date+'-'+end_date)
        $(function () {
            $('#blocktime').daterangepicker({
                //timePicker: true,
                startDate: (start_date != '') ? moment(start_date).startOf('hour') : moment().startOf('hour'),
                endDate: (end_date != '') ? moment(end_date).startOf('hour') : moment().startOf('hour'),
                minDate: (start_date != '') ? moment(start_date).startOf('hour') : new Date(),
                locale: {
                    format: 'M/DD/YY'
                }
            });
        });
    }

})

$(document).on('click', '#gerenal_slot', function () {
   $('#general_slot').modal('show');
});