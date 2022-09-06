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
})
