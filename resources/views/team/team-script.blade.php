<script type="text/javascript">
//jQuery.noConflict();
jQuery.noConflict();
    jQuery('.openModal').click(function(){
        jQuery('#add-team-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        makeTag();
    });

   
    var tagList = "{{jQueryshowTag}}";

    tagList = tagList.split(',');

    function makeTag(){
        jQuery('.myTag1').tagsInput({
            'autocomplete': {
                source: tagList
            } 
        });
    }

    jQuery(document).on('click', ".team-list-1", function() {
        var data_id = jQuery(this).attr('data-id');
        jQuery(".team-details").hide();
        jQuery("#team_detail_" + data_id).show();

        jQuery(".team-agent-list").hide();
        jQuery("#team_agents_" + data_id).show();
    });


    jQuery(".tag1").click(function() {
        var val = jQuery(this).text();

        var selectElement = jQuery('#teamtag').eq(0);
        var selectize = selectElement.data('selectize');
        selectize.additem(1, 2);
    });

    jQuery('.delete-team-form').on('submit', function() {
        team_agent_count = jQuery(this).attr('data-team-agent-count');
        if (team_agent_count > 0) {
            alert("Please assign other team to agents linked to this team before deleting");
            return false;
        }
        delete_team_confirmation = confirm("Do you want to delete the team?");
        if (delete_team_confirmation === true) {
            return true;
        }
        return false;
    });

    jQuery(".editIcon").click(function (e) {  
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
       
        var uid = jQuery(this).attr('teamId');

        jQuery.ajax({
            type: "get",
            url: "<?php echo url('team'); ?>" + '/' + uid + '/edit',
            data: '',
            dataType: 'json',
            success: function (data) {

                //jQuery('.page-title1').html('Hello');
                console.log('data');

                jQuery('#edit-team-modal #editCardBox').html(data.html);
                jQuery('#edit-team-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                makeTag();

            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    /* add Team using ajax*/
    jQuery("#add-team-modal #submitTeam").submit(function(e) {
            e.preventDefault();
    });

    jQuery(document).on('click', '.addTeamForm', function() { 
        var form =  document.getElementById('submitTeam');
        var formData = new FormData(form);
        var urls = "{{URL::route('team.store')}}";
        saveTeam(urls, formData, inp = '', modal = 'add-team-modal');
    });

    /* edit Team using ajax*/
    jQuery("#edit-team-modal #UpdateTeam").submit(function(e) {
            e.preventDefault();
    });

    jQuery(document).on('click', '.submitEditForm', function() {
        var form =  document.getElementById('UpdateTeam');
        var formData = new FormData(form);
        var urls =  document.getElementById('team_id').getAttribute('url');
        saveTeam(urls, formData, inp = 'Edit', modal = 'edit-team-modal');
    });

    function saveTeam(urls, formData, inp = '', modal = ''){

        jQuery.ajax({
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
                        jQuery("#" + modal + " .close").click();
                        location.reload(); 
                } else { console.log('wa-1');
                    jQuery(".show_all_error.invalid-feedback").show();
                    jQuery(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) { console.log('err1');
                if (response.status === 422) { console.log('err2');
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        jQuery("#" + key + "Input" + inp + " input").addClass("is-invalid");
                        jQuery("#" + key + "Input" + inp + " span.invalid-feedback").children(
                            "strong").text(errors[key][
                            0
                        ]);
                        jQuery("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    jQuery(".show_all_error.invalid-feedback").show();
                    jQuery(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });

    }

</script>