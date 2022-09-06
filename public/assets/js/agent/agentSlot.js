$(function(){
    var product_id = '';
    var vendor_id = '';
    var title = '';
    var block='';
    var appoin='';
    $(document).on('click', '.agent_slot_button', function() {
        // var psku = $('#sku').val();
        // var pid = $(this).attr('data-product_id');
        // var vid = $(this).attr('data-varient_id');
        // product_id = pid;
        // vendor_id  = vid;
        // title = $(this).attr('data-variant_title');
            //$("#agentTablePopup").dataTable().fnDestroy()
            // appoin =  $('#scheduleTable').DataTable({
            //     processing: true,
            //     scrollY: '200px',
            //     scrollCollapse: true,   
            //     responsive: true,
            //     ajax: `/client/getScheduleTableData?variant_id=${vid}&product_id=${pid}`,
            //     columns: [
            //         { data: 'id' },
            //         { data: 'user_name' },
            //         { data: 'start_date_time' },
            //         { data: 'end_date_time' },
            //         // { data: 'hr.salary' },
            //     ],
            // });
            //blockDataTable();
            //$('.sku-name').html(`(${title})`);
            $('#agentTablePopup').modal('show'); 
            
        
    });
    function blockDataTable(){
        $("#blockTimeTable").dataTable().fnDestroy()
        block = $('#blockTimeTable').DataTable({
            processing: true,
            scrollY: '200px',
            responsive: true,
            scrollCollapse: true,
            ajax: `/client/getScheduleTableBlockedData?variant_id=${vendor_id}&product_id=${product_id}`,
            columns: [
                { data: 'start_date_time' },
                { data: 'end_date_time' },
                { data: 'memo' },
                {data: "id" , render : function ( data, type, row, meta ) {
                    console.log(row);
                    return `<a href="javascript:void(0)" class="editbooking" data-memo='${row.memo}' data-row_id='${row.id}' data-start_date='${row.start_date_time}' data-end_date='${row.end_date_time}'><i class="mdi mdi-square-edit-outline"></i></a> |  <a href="javascript:void(0)"  class="deletebooking"  data-delete_booking_id='${row.id}'><i class="mdi mdi-delete"></i></a> `;
                }},
                
                // { data: 'hr.salary' },
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Add Manual Time',
                    attr: {id: 'add_manual_time' },
                    action: function ( e, dt, node, config ) {
                        //alert( 'Button activated' );
                        add_manual_block_time(product_id,vendor_id,title);
                    }
                }
            ]
        });
    }
    // function blockDataTable() {
    //     //$('#blockTimeTable').ajax.reload();
    // }

    function add_manual_block_time(product_id,variant_id,product_title){
        console.log(product_id);
        console.log(variant_id);
        console.log(product_title);
        Swal.fire({
            title: 'Add Manual Time',
            html: `<div class="addManualTime">
                        <div class="addManualTimeGroup" style="text-align:left;">
                            <label class="text-left">Start/End Date Time</label>    
                            <input id="blocktime" class="form-control" autofocus>
                        </div>
                        <div class="addManualTimeGroup mt-2" style="text-align:left;">
                            <label class="text-left">Memo</label>
                            <textarea style="height:100px" type="text" id="memo" class="swal2-input m-0" placeholder="Memo"></textarea>
                        </div>
                    </div>`,
            confirmButtonText: 'Submit',
            focusConfirm: false,
            preConfirm: () => {
              const memo = Swal.getPopup().querySelector('#memo').value
              const blocktime = Swal.getPopup().querySelector('#blocktime').value
              if (!memo || !blocktime) {
                Swal.showValidationMessage(`All feilds are required!!`)
              }
              return { blocktime: blocktime, memo: memo }
            },onOpen: function() {
                $(function() {
                    $('#blocktime').daterangepicker({
                      timePicker: true,
                      startDate: moment().startOf('hour'),
                      endDate: moment().startOf('hour').add(24, 'hour'),
                      minDate:new Date(),
                      locale: {
                        format: 'M/DD/YY hh:mm A'
                      }
                    });
                  });
            }
          }).then(async (result) => {
            var formData = {
              blocktime:result.value.blocktime,
              memo:result.value.memo,
              variant_id:variant_id,
              product_id:product_id,
              booking_slot:$('#blocktime').val()
            }
            await add_blocked_time(formData)
            // Swal.fire(`
            // blocktime: ${result.value.blocktime}
            //   memo: ${result.value.memo}
            // `.trim())
          })
    } 

    async function add_blocked_time(formData){
        
        axios.post(`/client/booking/addBlockSlot`, formData)
        .then(async response => {
         //console.log(response);
            if(response.data.success){
                //blockDataTable();
               // setInterval( function () {
                    
                //}, 30000 );
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Manual time added successfully!',
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
                block.ajax.reload();
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops',
                    text: 'This slot is already booked, Please try other.',
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
            }
        })
        .catch(e => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try again later!',
            })
        })    
    } 
    $(document).on('click', '.deletebooking', function() {
        var delete_booking_id = $(this).attr('data-delete_booking_id');
        deleteBooking(delete_booking_id)
    });
    async function deleteBooking(id){
        
        axios.get(`/client/booking/deleteSlot/${id}`)
        .then(async response => {
         //console.log(response);
            if(response.data.success){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Deleted successfully!',
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
                updateData();
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'This slot is already booked, Please try other.',
                })
            }
        })
        .catch(e => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try again later!',
            })
        })    

    }

    $(document).on('click', '.editbooking', function() {
        var booking_id = $(this).attr('data-row_id');
        var start_date = $(this).attr('data-start_date');
        var end_date = $(this).attr('data-end_date');
        var end_date = $(this).attr('data-end_date');
        var memo = $(this).attr('data-memo');
        console.log(booking_id);
        console.log(start_date);
        console.log(end_date);
        edit_manual_block_time(booking_id,start_date,end_date,memo)
        
    });

    function edit_manual_block_time(booking_id,start_date,end_date,memo){
     
        Swal.fire({
            title: 'Edit Manual Time',
            html: `<div class="addManualTime">
                        <div class="addManualTimeGroup" style="text-align:left;">
                            <label class="text-left">Start/End Date Time</label>    
                            <input id="blocktime" class="form-control" autofocus>
                            <input id="booking_id" type="hidden" class="form-control" name="booking_id" value="${booking_id}">
                        </div>
                        <div class="addManualTimeGroup mt-2" style="text-align:left;">
                            <label class="text-left">Memo</label>
                            <textarea style="height:100px" type="text" id="memo" class="swal2-input m-0" placeholder="Memo">${memo}</textarea>
                        </div>
                    </div>`,
            confirmButtonText: 'Submit',
            focusConfirm: false,
            preConfirm: () => {
              const memo = Swal.getPopup().querySelector('#memo').value
              const blocktime = Swal.getPopup().querySelector('#blocktime').value
              const booking_id = Swal.getPopup().querySelector('#booking_id').value
              if (!memo || !blocktime || !booking_id) {
                Swal.showValidationMessage(`All feilds are required!!`)
              }
              return { blocktime: blocktime, memo: memo , booking_id:booking_id}
            },onOpen: function() {
                $(function() {
                    $('#blocktime').daterangepicker({
                      timePicker: true,
                      startDate: moment().startOf('hour'),
                      endDate: moment().startOf('hour').add(24, 'hour'),
                      minDate:new Date(),
                      locale: {
                        format: 'M/DD/YY hh:mm A'
                      }
                    });
                  });
            }
          }).then(async (result) => {
            var formData = {
              blocktime:result.value.blocktime,
              memo:result.value.memo,
              booking_id:booking_id,
              booking_slot:$('#blocktime').val()
            }
            await update_blocked_time(formData)
            // Swal.fire(`
            // blocktime: ${result.value.blocktime}
            //   memo: ${result.value.memo}
            // `.trim())
          })
    } 

    async function update_blocked_time(formData){
        
        axios.post(`/client/booking/updateBlockSlot`, formData)
        .then(async response => {
         //console.log(response);
            if(response.data.success){
 
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Manual time Updated successfully!',
                    //footer: '<a href="">Why do I have this issue?</a>'
                    })
                updateData()
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'This slot is already booked, Please try other.',
              })
            }
        })
        .catch(e => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try again later!',
            })
        })    
    } 
    function updateData() {
        block.ajax.reload();
        appoin.ajax.reload();
    }
    if($('#calendar').length > 0){
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
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
                    hour12: "{{$hour12}}"
                }
            ],
            eventTimeFormat: { // like '14:30:00'
                hour: '2-digit',
                minute: '2-digit',
                hour12: "{{$hour12}}"
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

        calendar.render();
        }
})
