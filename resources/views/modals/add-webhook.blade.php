<div id="add-webhook-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Edit Message</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ route('set.message')}}" method="POST">
            @csrf
            <input type="hidden" name="notification_event_id" id="notification_event_id" value=""/>
            <div class="modal-body pb-0">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="mt-0 mb-2">Message</h4>
                        <div class="form-group">
                            <textarea name="message" id="webhook_url" class="form-control" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h4 class="mt-0 mb-2">Tags</h4>
                        <ul class="tags_list p-0">
                            <li>"driver_name"</li>
                            <li>"vehicle_model"</li>
                            <li>"plate_number"</li>
                            <li>"tracking_link"</li>
                            <li>"feedback_url"</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-blue waves-effect waves-light">Save</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->