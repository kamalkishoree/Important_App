
@extends('layouts.vertical', ['title' => 'Notifications'])

@section('css')
@endsection

@section('content')
@include('modals.add-webhook')
    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Settings</h4>
                </div>
            </div>
        </div>     
        
        <div class="row">
            <div class="col-xl-12">
                <div class="card-box">
                    <h4 class="header-title">Notifications</h4>
                    <p class="sub-header">
                        Send custom SMS's,emails and webhooks based on each trigger and customize the content by clicking on the pencil icon.         
                    </p>
                    @foreach($notification_types as $notification_type)
                    <div class="card-box">
                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <div class="text-sm-left">
                                    <h4 class="header-title">{{ $notification_type->name }}</h4>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <p class="btn btn-blue waves-effect waves-light text-sm-right">
                                    <i class="mdi mdi-plus-circle mr-1"></i> Add More
                                </p>
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>Events</th>
                                        <th>SMS</th>
                                        <th>EMAIL</th>
                                        <th>WEBHOOK</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notification_type->notification_events as $event)
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{ $event->name }}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" data-id="{{ $event->id }}" data-event-type="sms" id="smscustomSwitch_{{ $event->id}}" @if($event->is_checked_sms(auth()->user()->id))  checked @endif>
                                                <label class="custom-control-label" for="smscustomSwitch_{{ $event->id}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" data-id="{{ $event->id }}" data-event-type="email" id="emailcustomSwitch_{{ $event->id}}" @if($event->is_checked_email(auth()->user()->id))  checked @endif>
                                                <label class="custom-control-label" for="emailcustomSwitch_{{ $event->id}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" data-id="{{ $event->id }}" data-event-type="webhook" id="webhookcustomSwitch_{{ $event->id}}" @if($event->is_checked_webhook(auth()->user()->id))  checked @endif>
                                                <label class="custom-control-label" for="webhookcustomSwitch_{{ $event->id}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline web-hook-add" data-id="{{ $event->id }}" data-webhook-url="{{ $event->get_client_webhook_url(auth()->user()->id) }}"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div> 
            </div> 
        </div>
        
    </div>
@endsection

@section('script')
<script>
    $(function(){
        $('.event_type').change(function(){
            var current_value = this.checked ? 1 : 0;
            var notification_event_id=  $(this).attr('data-id');
            var notification_type = $(this).attr('data-event-type');
            formData = {
                current_value : current_value,
                notification_event_id : notification_event_id,
                notification_type : notification_type,
                _token : "{{ csrf_token() }}"
            };
            startLoader('body');
            $.ajax({
                method: "POST",
                headers: {
                    Accept: "application/json"
                },
                url: "/notification_update",
                data: formData,
                success: function(response) {
                    if (response.status == 'success') {

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
    });

    //webhook url updation //
    $(function(){
        $('.web-hook-add').click(function(){
            $('#notification_event_id').val($(this).attr('data-id'));
            $('#webhook_url').val($(this).attr('data-webhook-url'));
            $('#add-webhook-modal').modal('show');
        });
    });
</script>
@endsection