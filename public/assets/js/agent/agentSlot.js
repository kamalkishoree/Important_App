$(function(){
    
    dispatcherStorage.removeStorageAll();
    var product_id = vendor_id = title = block = appoin=agent_id = calendar='' ;
    var calendarEl = document.getElementById('calendar');
    $(document).on('click', '.agent_slot_button', function() {
        agent_id = $(this).data('agent_id');
        spinnerJS.showSpinner();
        $('#agentTablePopup').modal('show'); 
        fullCalendarInt(agent_id);
    });

    $(document).on('change','.slotTypeEdit',function(){
        var val = $(this).val();
        dispatcherStorage.setStorageSingle('SlotType',val);
        if(val == 'date') {
            if($('.forDate').length >0){
                $(".forDate").fadeIn(1000);
            }else{
                $(".forDateEdit").fadeIn(1000);
            }
           
        } else{
            if($('.forDate').length >0){
                $(".forDate").fadeOut(500);
            }else{
                $(".forDateEdit").fadeOut(500);
            }
            
        }
    })
    
    $(document).on('change', '#edit_slot_date', function() {
        var edit_slot_date = $(this).val();
        dispatcherStorage.setStorageSingle('SlotDate',edit_slot_date);
    });

    $(document).on('change', '#recurring', function(e) {
        if(e.target.checked){
            $(".weekDays").fadeIn(1000);
        }else{
            $(".weekDays").fadeOut(1000);
        }
        dispatcherStorage.setStorageSingle('recurring',e.target.checked);
    });


    $(document).on('click', '#deleteSlotBtn', function() {
        var date = $('#edit_slot_date').val();
        
        $('#edit-slot-modal #deleteSlotDate').val(date);
        Swal.fire({
            title: 'Are you sure? You want to delete this slot.',
            confirmButtonText: 'Yes',
            focusConfirm: false,
            preConfirm: () => {

                const SlotDayid   =   dispatcherStorage.getStorage('SlotDayid');
                const SlotId   =   dispatcherStorage.getStorage('SlotId');
                const SlotType   =  dispatcherStorage.getStorage('SlotType');
                const SlotTypeOld   =  dispatcherStorage.getStorage('SlotTypeOld');
                const SlotDate   =  dispatcherStorage.getStorage('SlotDate');



                // const start_time = Swal.getPopup().querySelector('#edit_start_time').value
                // const end_time = Swal.getPopup().querySelector('#edit_end_time').value
                // const slot_type_edit = document.querySelector('input[name=radio-group]').value 
                // const edit_type_id = Swal.getPopup().querySelector('#edit_type_id').value
                // const edit_slot_date = Swal.getPopup().querySelector('#edit_slot_date').value
    //,start_time:start_time,end_time:end_time,slot_type_edit:slot_type_edit,edit_type_id:edit_type_id,edit_slot_date:edit_slot_date
               
              return { SlotDayid: SlotDayid, SlotId: SlotId,SlotType:SlotType,SlotTypeOld:SlotTypeOld,SlotDate:SlotDate }
            },onOpen: function() {
            }
          }).then(async (result) => {
            var formData = {
                slot_day_id:result.value.SlotDayid,
                slot_id:result.value.SlotId,
                slot_type:result.value.SlotType,
                old_slot_type:result.value.SlotTypeOld,
                slot_date:result.value.SlotDate,
                agent_id:agent_id
                // start_time:start_time,
                // end_time:end_time,
                // slot_type_edit:slot_type_edit,
                // edit_type_id:edit_type_id,
                // edit_slot_date:edit_slot_date
            }
            //console.log(formData);
            await deleteSlot(formData)
            // Swal.fire(`
            // blocktime: ${result.value.blocktime}
            //   memo: ${result.value.memo}
            // `.trim())
          })
        // if (confirm("Are you sure? You want to delete this slot.")) {
        //    console.log('sadf');
        // }
        return false;
    })

    async function fullCalendarInt(agent_id){
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
                            const recurring = dispatcherStorage.getStorage('recurring');

                            const memo = dispatcherStorage.getStorage('memo');
                            const booking_type = Swal.getPopup().querySelector('#booking_type').value


                            var week_day = [];

                            //const slotType = dispatcherStorage.getStorage('SlotType')
                           
                            $.each($("input:checkbox[name='week_day[]']:checked"), function () {
                                week_day.push($(this).val());
                            });
                           
                            if (start_time=='' && end_time=='' && blocktime=='' && memo=='' && booking_type=='') {
                               Swal.showValidationMessage(`All feilds are required!!`)
                               return false;
                            }
                            if(recurring == 'true'){
                                console.log(week_day);
                                if (!week_day.length>0) {
                                    Swal.showValidationMessage(`Please select days to recurring!!`)
                                    return false;
                                }
                                
                            }
                            return { start_time: start_time, end_time: end_time,week_day:week_day,blocktime:blocktime,recurring:recurring,booking_type:booking_type,memo:memo}
                        },onOpen: function() {
                            initDatetimeRangePicker();
                            // var save_slot_url = `/agent/slot/${agent_id}`
                            // $('#slot-event').setAttribute('action',save_slot_url);
                        }
                      }).then(async (result) => {
                        var formData = {
                            start_time:result.value.start_time,
                            end_time:result.value.end_time,
                            week_day:result.value.week_day,
                            blocktime:result.value.blocktime,
                            recurring:result.value.recurring,
                            agent_id:agent_id,
                            booking_type:result.value.booking_type,
                            memo:result.value.memo
                          }
                          console.log(formData);
                          await add_slot_time(formData)
                       
                        // Swal.fire(`
                        // blocktime: ${result.value.blocktime}
                        //   memo: ${result.value.memo}
                        // `.trim())
                      })
                  
                    // var save_slot_url = `/agent/slot/${agent_id}`
                    // $('#add-slot-modal').modal({
                    //                 //backdrop: 'static',
                    //                 keyboard: false
                    //             });
                   
                    calendar.addEvent({
                        title: '',
                        start: arg.start,
                        end: arg.end,
                        allDay: arg.allDay
                    })
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
                    var calender_data_url  = Agent_calender_url.replace(":id", agent_id) 
                    $.ajax({
                        url: calender_data_url,
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
                    Swal.fire({
                        title: 'Edit working hours',
                        html: EditSlotHtml,
                        confirmButtonText: 'Submit',
                        focusConfirm: false,
                        customClass: "edit-slot-agent",
                        preConfirm: () => {
                            const start_time = Swal.getPopup().querySelector('#edit_start_time').value
                            const end_time = Swal.getPopup().querySelector('#edit_end_time').value
                            const slot_type_old = dispatcherStorage.getStorage('SlotTypeOld')
                            const edit_type_id = Swal.getPopup().querySelector('#edit_type_id').value
                            const edit_slot_date = Swal.getPopup().querySelector('#edit_slot_date').value
                            const edit_type = Swal.getPopup().querySelector('#edit_type').value
                          
                          
                            if (!start_time || !end_time  ) {
                              Swal.showValidationMessage(`All feilds are required!!`)
                            }
                            return { start_time: start_time, end_time: end_time, edit_slot_date:edit_slot_date}
                        },onOpen: function() {
                            // var save_slot_url = `/agent/slot/${agent_id}`
                            // $('#slot-event').setAttribute('action',save_slot_url);
                        }
                      }).then(async (result) => {
                        var formData = {
                            start_time:result.value.start_time,
                            end_time:result.value.end_time,
                            edit_type:dispatcherStorage.getStorage('edit_type'),
                            edit_type_id:dispatcherStorage.getStorage('edit_type_id'),
                            edit_day:dispatcherStorage.getStorage('edit_day'),
                            edit_slot_date:result.value.edit_slot_date,
                            agent_id:agent_id,
                            slot_type_edit:dispatcherStorage.getStorage('SlotType'),
                            
                          }
                         
                          await add_slot_time(formData,'edit')
                       
                        // Swal.fire(`
                        // blocktime: ${result.value.blocktime}
                        //   memo: ${result.value.memo}
                        // `.trim())
                      })
                  
                    // console.log(ev.event.extendedProps);
                    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
                    var day = ev.event.start.getDay() + 1;
    
                    // document.getElementById('edit_type').value = ev.event.extendedProps.type;
                    // document.getElementById('edit_day').value = day;
                    // document.getElementById('edit_type_id').value = ev.event.extendedProps.type_id;
    
                    // Delete Slot Form
                    /**storage */
                        dispatcherStorage.setStorageSingle('SlotDayid',ev.event.extendedProps.type_id)
                        dispatcherStorage.setStorageSingle('SlotId',ev.event.extendedProps.slot_id);
                        dispatcherStorage.setStorageSingle('SlotType',ev.event.extendedProps.type);
                        dispatcherStorage.setStorageSingle('SlotTypeOld',ev.event.extendedProps.type);
                        dispatcherStorage.setStorageSingle('edit_type',ev.event.extendedProps.type);
                        dispatcherStorage.setStorageSingle('edit_day',day);
                        dispatcherStorage.setStorageSingle('edit_type_id',ev.event.extendedProps.type_id);
                    /**storage */
                    document.getElementById('SlotDayid').value = ev.event.extendedProps.type_id;
                    document.getElementById('SlotId').value = ev.event.extendedProps.slot_id;
                    document.getElementById('SlotType').value = ev.event.extendedProps.type;
                    document.getElementById('SlotTypeOld').value = ev.event.extendedProps.type;
    
                    if(ev.event.extendedProps.type == 'date'){
                        $("#edit_slotDate").prop("checked", true);
                        $(".forDateEdit").delay(1000).show(0);
                        //$("#update-event .forDateEdit").show();
                    }else{
                        $("#edit_slotDay").prop("checked", true);
                        //$("#update-event .forDateEdit").hide();
                        $(".forDateEdit").delay(1000).hide(0);
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
                    
                   
    
                    $('#edit_slot_date').flatpickr({
                        minDate: "today",
                        defaultDate: (ev.event.extendedProps.type == 'date') ? ev.event.start : ev.event.start
                    });
    
                    $('#edit_slotlabel').text('Edit For All ' + days[day-1] + '   ');
    
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
    async function add_slot_time(formData,action='add'){
        var actionUrl = (action == 'add') ? 'add_slot' : 'update_slot';
        axios.post(`agent/${actionUrl}`, formData)
        .then(async response => {
         console.log(response);
            if(response.data.status == "Success"){
                //blockDataTable();
               // setInterval( function () {
                    
                //}, 30000 );
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.data.message,
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
                fullCalendarInt(agent_id)
             
            } else{
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
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Oops...',
            //     text: 'Something went wrong, try again later!',
            // })
        })    
    } 

    async function deleteSlot(formData){
        console.log(formData);
        axios.post(`agent/slot/delete`, formData)
        .then(async response => {
         console.log(response.data.status);
            if(response.data.status == "Success"){
                
                //blockDataTable();
               // setInterval( function () {
                    
                //}, 30000 );
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.data.message,
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
                fullCalendarInt(agent_id)
            } else{
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

   function  initDatetimeRangePicker(){
        $(function() {
            $('#blocktime').daterangepicker({
              //timePicker: true,
              startDate: moment().startOf('hour'),
              endDate: moment().startOf('hour').add(24, 'hour'),
              minDate:new Date(),
              locale: {
                format: 'M/DD/YY'
              }
            });
          });
    }
  
})
