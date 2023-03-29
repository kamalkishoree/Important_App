<div class="col-md-4 mb-3">
    <div class="card-box h-100">
        <form method="POST" class="h-100" action="">
            @csrf
            <input type="hidden" name="rating_type" value="1">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title text-uppercase mb-0">{{ __(getAgentNomenclature()) }} {{__('Rating Types')}}</h4>
                        <button class="btn btn-outline-info d-block" id="add_rating_type_btn" type="button"> {{__('Add')}} </button>
                    </div>
                    <div class="table-responsive mt-3 mb-1">
                        <table class="table table-centered table-nowrap table-striped" id="Rating_datatable">
                            <thead>
                                <tr>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Take Review')}}</th>
                                    {{-- <th>{{__('Status')}}</th> --}}
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody id="review_type_list">
                                    <tr align="center">
                                        <td colspan="4" style="padding: 20px 0">{{__('Result not found.')}}</td>
                                    </tr>
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>