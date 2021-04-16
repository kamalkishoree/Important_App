<div id="add-webhook-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Message</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ route('set.message')}}" method="POST">
            @csrf
            <input type="hidden" name="notification_event_id" id="notification_event_id" value=""/>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Message</h4>
                        <div class="form-group">
                            ​<textarea name="message" id="webhook_url" class="form-control"  rows="5" cols="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h4>Tags</h4>
                        <ul class="tags_list">
                            <li>"driver_name"</li>
                            <li>"vehicle_model"</li>
                            <li>"plate_number"</li>
                            <li>"tracking_link"</li>
                            <li>"feedback_url"</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-blue waves-effect waves-light">Save</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->