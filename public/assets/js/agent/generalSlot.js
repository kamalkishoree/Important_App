$(function(){
    var vendorCityTable = '' ;
     $(document).on('click', '#gerenal_slot', function(event) {
         initDataTable();
        $('#general_slot').modal('show');
        select2Intent();
         event.preventDefault();
         //loadMap();
     });
     function select2Intent (){
        $("#week_days").select2({
            allowClear: true,
            width: "resolve",
            placeholder: "Select Week Days"
        });
    }
 
     function initDataTable(){
         $("#generalSlot").dataTable().fnDestroy()
         vendorCityTable =  $('#generalSlot').DataTable({
             processing: true,
             scrollY: '600px',
             scrollX: '200px',
             scrollCollapse: true,   
             responsive: true,
             ordering: false,
             lengthChange: false,
             searching: false,
             ajax: `/general/slot/get`,
             columns: [  
                 { data: 'id' },
                 { data: 'start_time' },
                 { data: 'end_time' },
                 { data: 'edit_action', class:'text-center', name: 'edit_action', orderable: false, searchable: false, "mRender":function(data, type, row, meta ){
                    return `<div class='form-ul'><div class='inner-div d-inline-block'><form method='POST' ><div class='form-group mb-0'><button type='button' class='btn btn-primary-outline delete-general_slot' data-destroy_id='${row.id}' data-destroy_id='${row.id}'><i class='mdi mdi-delete'></i></button></div></form></div></div>`
                }
            },
             ],
             buttons: [
                 {
                     text: 'Add City',
                     attr: {id: 'add_city' },
                     action: function ( e, dt, node, config ) {
                         //alert( 'Button activated' );
                         
                     }
                 }
             ]
         });
     }
     $(document).on('click', '.submitSaveGeneralSlot', function(e) {
         $('.submitSaveGeneralSlot').attr("disabled", true); 
         var slot_id = $("#general_slot input[name=general_slot_id]").val();
         if (slot_id) {
             var post_url = `/general/slot/save`;
             var msg_text = `Update successfully`;
         } else {
             var post_url = `/general/slot/save`;
             var msg_text = `Added successfully`;
         }
         // console.log(vendor_city_id);
         // return false;
         var formData = new FormData(document.getElementById("generalSlotForm"));
         axios.post(post_url, formData)
         .then(async response => {
          console.log(response);
                 if (response.data.status == 'Success') {
                     
                     $('#generalSlotForm')[0].reset();
                     document.getElementById('general_slot_id').value = ''
                     vendorCityTable.ajax.reload();
                     Swal.fire({
                         icon: 'success',
                         title: 'Success',
                         text: response.data.message,
                     })
                     $('.submitSaveGeneralSlot').attr("disabled", false); 
                   //$('#vendor_city').modal('hide');
             
                } else {
                     $('.submitSaveGeneralSlot').attr("disabled", false); 
                     Swal.fire({
                         icon: 'info',
                         title: 'Error',
                         text: response.data.message,
                     })
                }
            
         })
         .catch(e => {
             console.log(e);
             $('.submitSaveGeneralSlot').attr("disabled", false); 
                 let errors = e.response.data.errors;
                 $('#generalSlotForm input').each(function(key) {
                    $input = this;
                    $($input).removeClass("is-invalid");
                 });

                 Object.keys(errors).forEach(function(key) {
                     var data = key.replace('.','_');
                     $("#" + key + "Input input").addClass("is-invalid");
                     $("#" + key + "Input span.invalid-feedback").children(
                         "strong").text(errors[key][
                         0
                     ]);
                     $("#" + key + "Input span.invalid-feedback").show();
                    //  console.log(data);
                    //  $("." + data ).addClass("is-invalid");
                 });
             })    
     });
 
     $(document).on('click','.delete-general_slot',function(){
         var destroy_id = $(this).data('destroy_id');
         Swal.fire({
            icon: 'info',
             title: 'Warning!',
             text: 'Are you sure?',
           
           }).then(({value}) => {
            if (value === "Yes") {
                 deleteGeneralSlot(destroy_id);
                
              }
              //  else {
             //     Swal.fire({
             //         icon: 'error',
             //         title: 'Oops...',
             //         text: 'Entered wrong text!',
             //         //footer: '<a href="">Why do I have this issue?</a>'
             //     })
             // }
           });
     });
 
     function deleteGeneralSlot(destroy_id){
         
         axios.get(`/general/slot/destroy/${destroy_id}`)
         .then(async response => {
            vendorCityTable.ajax.reload();
              console.log(response);
              if(response.data.success) {
                 // Swal.fire(
                 //     'Deleted successfully!',                                    
                 //     'success'
                 // )
                 Swal.fire({
                     icon: 'success',
                     title: 'Success',
                     text: 'Deleted successfully!',
                     //footer: '<a href="">Why do I have this issue?</a>'
                 })
              }
              
         })
         .catch(e => {
             Swal.fire(
                 'Something went wrong, try again later!',                                    
                 'error'
             )
             Swal.fire({
                 icon: 'error',
                 title: 'Oops...',
                 text: 'Something went wrong, try again later!',
                 //footer: '<a href="">Why do I have this issue?</a>'
             })
         })    
     }
 
  
     $(document).on("click", ".add_cities", function() {
         let tag_id = $(this).data('id');
         $('#vendor_city input[name=vendor_city_id]').val(tag_id);
         if(tag_id) {
             showCities(tag_id);
         } 
         
         
     });
 
     function showCities(tag_id){
         $.ajax({
             method: 'GET',
             
             url: `/client/vendor_city/show/${tag_id}`,
             success: function(response) {
                 console.log(response);
                if (response.status = 'Success') {
                     var data = response.data;
                     if(data!= undefined && data!=null){
                         document.getElementById('city_latitude').value = data.latitude;
                         document.getElementById('city_longitude').value = data.longitude;
                         document.getElementById('city-address').value = data.address;
                         document.getElementById('place_id').value = data.place_id;
                         var image = data.image.proxy_url+'100/100'+data.image.image_path;
                         var html = `<input type="file" id="vendor_city_image" name="vendor_city_image" class="dropify form-control" data-default-file="${image}" required />`;
                         $('.vendor_city_image').html(html);
                         $('.dropify').dropify();
                           $.each(response.data.translations, function( index, value ) {
                             $('#vendor_city #city_name_'+value.language_id).val(value.name);
                         });
                         
                         // $(".dropify").attr("data-default-file", "https://dummyimage.com/600x400/000/fff");
                         // $('.dropify').dropify();
                     }
                     
                 //   $("#add_product_tag_modal input[name=tag_id]").val(response.data.id);
                 //   $('#add_product_tag_modal #standard-modalLabel').html('Update Product Tag');
                 //   $('#add_product_tag_modal').modal('show');
                 //   $.each(response.data.translations, function( index, value ) {
                 //     $('#add_product_tag_modal #product_tag_name_'+value.language_id).val(value.name);
                 //   });
                }
             },
             error: function() {
 
             }
         });
     }
     
 });