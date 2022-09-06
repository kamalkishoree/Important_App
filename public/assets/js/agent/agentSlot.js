$(function(){
    var product_id = vendor_id = title = block = appoin = calendar='';
    var calendarEl = document.getElementById('calendar');
    $(document).on('click', '.agent_slot_button', function() {
            spinnerJS.showSpinner();
            $('#agentTablePopup').modal('show'); 
            fullCalendarInt();
    });


   async function fullCalendarInt(){
        if($('#calendar').length > 0){
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
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
                selectable: true,
                selectMirror: true,
                height: 'auto',
                editable: false,
                nowIndicator: true,
                eventMaxStack: 1,
                select: function(arg) {
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
    
                    if (arg.allDay == true) {
                        document.getElementById('start_time').value = "00:00";
                        document.getElementById('end_time').value = "23:59";
                    } else {
                        var startTime = ("0" + arg.start.getHours()).slice(-2) + ":" + ("0" + arg.start.getMinutes()).slice(-2);
                        var EndTime = ("0" + arg.end.getHours()).slice(-2) + ":" + ("0" + arg.end.getMinutes()).slice(-2);
    
                        document.getElementById('start_time').value = startTime;
                        document.getElementById('end_time').value = EndTime;
                    }
    
    
                    $('#slot_date').flatpickr({
                        minDate: "today",
                        defaultDate: arg.start
                    });
                },
               
    
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: "{{route('vendor.calender.data', $vendor->id)}}",
                        type: "GET",
                        data: "start="+info.startStr+"&end="+info.endStr,
                        dataType:'json',
                        success: function (response) {
                            var startDate = moment(info.start).format('MMM DD');
                            var endDate = moment(info.end - 1).format('DD, YYYY');
                            $("#calendar_slot_alldays_table thead th").html(startDate+" - "+endDate);
                            $("#calendar_slot_alldays_table tbody").html("");
                            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            var slotDayList = [];
                            var events = [];
                            $.each(response, function(index, data){
                                var slotDay = parseInt(moment(data.start).format('d')) + 1;
                                    var slotStartTime = moment(data.start).format('h:mm A');
                                    var slotEndTime = moment(data.end).format('h:mm A');
                                
    
                                $.each(days, function(key, value){
                                    if(slotDay == key + 1){
                                        if(slotDayList.includes(slotDay)){
                                            $("#calendar_slot_alldays_table tbody tr[data-slotDay='"+slotDay+"'] td:nth-child(2)").append("<br>"+slotStartTime+" - "+slotEndTime);
                                        }
                                        else{
                                            $("#calendar_slot_alldays_table tbody").append("<tr data-slotDay="+slotDay+"><td>"+value+"</td><td>"+slotStartTime+" - "+slotEndTime+"</td></tr>");
                                        }
                                    }
                                });
                                slotDayList.push(slotDay);
    
                                events.push({
                                    title: data.title,
                                    start: data.start,
                                    end: data.end,
                                    type: data.type,
                                    color: data.color,
                                    type_id: data.type_id,
                                    slot_id: data.slot_id,
                                    slot_dine_in: data.slot_dine_in,
                                    slot_takeaway: data.slot_takeaway,
                                    slot_delivery: data.slot_delivery,
                                    service_area: data.service_area,
                                });
                            });
                            successCallback(events);
                        }
                    });
                },
                eventResize: function(arg) {
                },
                eventClick: function(ev) {
                    $('#edit-slot-modal').modal({
                        //backdrop: 'static',
                        keyboard: false
                    });
                    // console.log(ev.event.extendedProps);
                    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
                    var day = ev.event.start.getDay() + 1;
    
                    document.getElementById('edit_type').value = ev.event.extendedProps.type;
                    document.getElementById('edit_day').value = day;
                    document.getElementById('edit_type_id').value = ev.event.extendedProps.type_id;
    
                    // Delete Slot Form
                    document.getElementById('deleteSlotDayid').value = ev.event.extendedProps.type_id;
                    document.getElementById('deleteSlotId').value = ev.event.extendedProps.slot_id;
                    document.getElementById('deleteSlotType').value = ev.event.extendedProps.type;
                    document.getElementById('deleteSlotTypeOld').value = ev.event.extendedProps.type;
    
                    if(ev.event.extendedProps.type == 'date'){
                        $("#edit_slotDate").prop("checked", true);
                        $(".modal .forDateEdit").show();
                    }else{
                        $("#edit_slotDay").prop("checked", true);
                        $(".modal .forDateEdit").hide();
                    }
    
                    if(ev.event.extendedProps.slot_delivery == 0){
                        $("#edit_delivery").prop("checked", false);
                    }
                    if(ev.event.extendedProps.slot_takeaway == 0){
                        $("#edit_takeaway").prop("checked", false);
                    }
                    if(ev.event.extendedProps.slot_dine_in == 0){
                        $("#edit_dine_in").prop("checked", false);
                    }
                    
                    // display selected service areas 
                    var service_areas = ev.event.extendedProps.service_area;
                    $("#edit_slot_service_area").val(service_areas).trigger('change');
    
                    $('#edit_slot_date').flatpickr({
                        minDate: "today",
                        defaultDate: (ev.event.extendedProps.type == 'date') ? ev.event.start : ev.event.start
                    });
    
                    $('#edit-slot-modal #edit_slotlabel').text('Edit For All ' + days[day-1] + '   ');
    
                    var startTime = ("0" + ev.event.start.getHours()).slice(-2) + ":" + ("0" + ev.event.start.getMinutes()).slice(-2);
                    document.getElementById('edit_start_time').value = startTime;
    
                    var EndTime = '';
    
                    if (ev.event.end) {
                        EndTime = ("0" + ev.event.end.getHours()).slice(-2) + ":" + ("0" + ev.event.end.getMinutes()).slice(-2);
                    }
                    document.getElementById('edit_end_time').value = EndTime;
    
                }
            });
            setTimeout(async ()=>{
                await calendar.render();
                spinnerJS.hideSpinner()
            },200)
           
          
        }

    }
  
})
