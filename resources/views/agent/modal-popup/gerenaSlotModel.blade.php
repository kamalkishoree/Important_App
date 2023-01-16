<div id="general_slot" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("General Slot")}} </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class='row'>
                <div class='col-md-5'>
                    <form id="generalSlotForm" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
                        @csrf
                        <div id="save_vendor_city" class="p-2 m-2 bg-light" style="border-radius: 15px;">
                           <input type="hidden" id="general_slot_id" name="general_slot_id" value="">
                           
                          
                            <div class="row">
                                {{-- <div class="col-md-12">
                                    <div class="form-group mb-3" id="week_daysInput">
                                        {!! Form::label('title', __('Select Days'),['class' => 'control-label']) !!}
                                        <select name="week_days[]" class="form-control select2" id="week_days" multiple="multiple">

                                            @foreach (config('constants.weekDay') as $key=>$day)
                                                <option value="{{ $key}}">{{$day}}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div> --}}
                              
                                <div class="col-md-12">
                                    <div class="form-group"  id="start_timeInput">
                                        <label class="control-label">{{ __("Start Time(24 hours format)") }}</label>
                                        <input class="form-control" placeholder="Start Time" type="time" name="start_time" id="start_time" required />
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group"  id="end_timeInput">
                                        <label class="control-label">{{ __("End Time(24 hours format)") }}</label>
                                        <input class="form-control" placeholder="End Time" type="time" name="end_time" id="end_time" required />
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer p-0">
                                <button type="button" class="btn btn-primary submitSaveGeneralSlot">{{ __("Save") }}</button>
                             </div>
            
            
                        </div>
                     </form>
                </div>
                <div class='col-md-7'> 
                    <table id="generalSlot" class="display" style="width:100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Start Time') }}</th>
                                <th>{{ __('End Time') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

