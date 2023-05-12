<div id="adddriverRatingemodal" class="modal al fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title" id="adddriverRatingeTitle">{{ __('Add Attribute') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addDriverRatingeForm" method="post" enctype="multipart/form-data" action="{{route('attribute.store')}}">
                @csrf
                <input type="hidden" name="attribute_for" value="2">
                <div class="modal-body" id="addDriverRatingbox">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light addAttributeSubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>