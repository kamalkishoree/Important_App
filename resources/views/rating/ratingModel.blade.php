<div id="add_rating_type_modal" class="modal fade" tabindex="-1" role="dialog"
aria-labelledby="rating-modalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header border-bottom">
            <h4 class="modal-title" id="rating-modalLabel"> {{ __('Rating Type') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form id="RatingTypeForm" method="POST" action="javascript:void(0)">
                @csrf
                <div id="RatingTypeForm_media">
                    <input type="hidden" name="rating_type_id" value="">
                    <div class="row">

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group position-relative">
                                        <label for="">{{ __('Name') }}</label>
                                        <input class="form-control" name="rating_title" type="text"
                                            id="rating_title">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="">{{ __('Take Reviews ?') }} </label>
                            <div class="custom-switch redio-all">
                                <input type="checkbox" value="1"
                                    class="custom-control-input alcoholic_item large-icon"
                                    id="is_take_reviews" name="is_take_reviews">
                                <label class="custom-control-label" for="is_take_reviews"></label>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary submitRatingType">{{__('Save')}}</button>
        </div>
    </div>
</div>
</div>
