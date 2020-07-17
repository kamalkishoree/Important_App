/**
 * Submit Client INFO
 */
$(document).ready(function() {
    $('#add-agent-modal').on('hidden.bs.modal', function(e) {
        $(this).find('#submitAgent')[0].reset();
    });
});

$("#submitAgent").submit(function(e) {
    e.preventDefault();
    //var formData = $(this).serializeArray();
    var formData = new FormData(this);
    console.log(formData);
    $.ajax({
        method: "POST",
        headers: {
            Accept: "application/json"
        },
        url: "/agent",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.status == 'success') {
                $("#add-agent-modal").modal('hide');
                console.log('vince', $(".alert-success").attr('class'));
                $(".alert-success").removeClass('d-none');
                $(".alert-success").text(response.message);

            } else {
                $(".show_all_error.invalid-feedback").show();
                $(".show_all_error.invalid-feedback").text(response.message);
            }
        },
        error: function(response) {
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                Object.keys(errors).forEach(function(key) {
                    $("#" + key + "Input input").addClass("is-invalid");
                    $("#" + key + "Input span.invalid-feedback").children(
                        "strong").text(errors[key][
                        0
                    ]);
                    $("#" + key + "Input span.invalid-feedback").show();
                });
            } else {
                $(".show_all_error.invalid-feedback").show();
                $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
            }
        }
    });
});