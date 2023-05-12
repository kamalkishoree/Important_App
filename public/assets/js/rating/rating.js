$(function () { 
  //  alert('asdf');
  var RatingTypedatatable ='';
  RatingTypeTable();

});


$(document).on('click', '#add_rating_type_btn', function () {
    document.getElementById("RatingTypeForm").reset();
    $('#add_rating_type_modal input[name=rating_type_id]').val("");
    $('#add_rating_type_modal').modal('show');
    $('#add_rating_type_modal #rating-modalLabel').html(_language.getLanString('Rating Type'));
});

$(document).on('click', '.submitRatingType',async function () {
    var formData = new FormData(document.getElementById("RatingTypeForm"));
    console.log(formData);
    await saveRating(formData) 
});
$(document).on('click', '.editRatingTypeCard', function(e) {
    e.preventDefault();
    var rating_id =$(this).attr('data-rating_id');
    getRating(rating_id)

});

async function saveRating(formData) {

    var url = `/rating_type/create`;
    
    axios.post(url, formData)
        .then(async response => {
            RatingTypedatatable.ajax.reload();
            $('#add_rating_type_modal').modal('hide');

            if (response.data.status == "Success") {
                $.NotificationApp.send("Success", response.data.message, "top-right", "#5ba035",
                "success");
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.data.message,
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
              

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
            $.NotificationApp.send("Error", response.data.message, "top-right", "#ab0535","error");
           
        })
}

function RatingTypeTable(){
    //$("#giftCard_datatable").dataTable().fnDestroy()
    RatingTypedatatable = $('#Rating_datatable').DataTable({
        processing: true,
        responsive: true,
        searching: false,
        scrollY: '200px',
        responsive: true,
        destroy: true,
        scrollCollapse: true,
        lengthChange: false,
        ajax: `/rating_type/list`,
        columns: [
            // { data: 'DT_RowIndex' },
            {
                data: 'title',
                name: 'title',
                orderable: false,
                searchable: false,
            },
            { data: 'take_review',
            orderable: false },
            { data: 'action',
            orderable: false, }
        
        ]
     
    });
}

function getRating(id){
    $.ajax({
        type: "get",
        url: `/rating_type/show/${id}`,
        dataType: 'json',
        success: function(response) {
            var rating_type = response.data;
            console.log(rating_type);
            $('#add_rating_type_modal input[name=rating_type_id]').val( rating_type?.id) 
            $('#add_rating_type_modal input[name=rating_title]').val(rating_type?.title) 
            if(rating_type?.is_take_reviews ==1){
                document.getElementById("is_take_reviews").checked = true
            }
           $('#add_rating_type_modal').modal('show');
     
        },
        error: function(data) {
            console.log('data2');
        }
    });
}
$(document).on('click', '.deleteRatingType', function(e) {
    e.preventDefault();
    var rating_id =$(this).attr('data-rating_id');
 
    Swal.fire({
        title: 'Warning!',
        text: 'Are you sure?',
        icon: 'warning',
      }).then(({value}) => {
        console.log(value);
            if (value === true) {
                deleteRatingType(rating_id);
            } 
      });

});

function deleteRatingType(id){
    axios.get(`/rating_type/delete/${id}`)
    .then(async response => {
   
        if(response.data.success){
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.data.message,
            })
            //sweetAlert.success('Success',response.data.message);
        } else{
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.data.message,
              
            })
          //  sweetAlert.error('',response.data.message);
        }
        setTimeout(() => {
            RatingTypedatatable.ajax.reload();
        },1000);
    })
    .catch(e => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "oppes.....",
          
        })
        //sweetAlert.error();
    })    
}
 // Attribute script

$(document).on('click', '.add_driver_rating_quiestionbtn', function(e) {
    console.log('click function called');
    e.preventDefault();
    getDriverRatingQ();

});
function getDriverRatingQ(ratingQuesId = 0){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
   
    $.ajax({
        type: "get",
        url: `/attribute/create?for=2&&attribute_id=${ratingQuesId}`,
        data: '',
        dataType: 'json',
        success: function(data) {
            console.log(data);
            var title = _language.getLanString('Add Driver Reviwe Question');
            if(ratingQuesId !=0){
                var title = _language.getLanString('Edit Driver Reviwe Question');
            }
            $('#adddriverRatingeTitle').html(title);
            
            $('#adddriverRatingemodal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#addDriverRatingeForm #addDriverRatingbox').html(data.html);
        },
        error: function(data) {
            console.log('data2');
        }
    });
}
$(document).on('click', '.editDriverratingQBtn', function(e) {
    var rating_id =$(this).attr('data-id');
    getDriverRatingQ(rating_id);

});
$(document).on('click', '.addOptionRow-attribute-edit', function(e) {
    var d = new Date();
    var n = d.getTime();
    var $tr = $('.optionTableEditAttribute tbody>tr:first').next('tr');
    var $clone = $tr.clone();
    $clone.find(':text').val('');
    $clone.find(':hidden').val('');
    $clone.find('.hexa-colorpicker').attr("id", "hexa-colorpicker-" + n);
    $clone.find('.lasttd').html('<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>');
    $('.optionTableEditAttribute').append($clone);

});
$("#addDriverRatingeForm").on('click', '.deleteCurRow', function() {
    $(this).closest('tr').remove();
});
$(document).on('click', '.deleteAttributebtn', function(e) {
    var attribute_id =$(this).attr('data-id');
   
    Swal.fire({
        title: 'Warning!',
        text: 'Are you sure?',
        icon: 'warning',
      }).then(({value}) => {
        console.log(value);
            if (value === true) {
                deleteRatingQuestion(attribute_id);
            } 
      });
   // getDriverRatingQ(rating_id);

});
function deleteRatingQuestion(attribute_id){
    axios.get(`/attribute/delete/${attribute_id}`)
    .then(async response => {
   console.log(response);
        if(response.data.status){
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.data.message,
            })
        } else{
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.data.message,
              
            })
        }
        setTimeout(() => {
            location.reload();
        },1000);
    })
    .catch(e => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "oppes.....",
          
        })
        //sweetAlert.error();
    })    
}