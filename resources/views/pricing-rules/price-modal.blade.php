<div id="add-pricing-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Add Pricing Rule")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_customer" action="{{ route('pricing-rules.store') }}" method="POST">
                @csrf
                <div class="modal-body px-3 py-0">

                    <div class="row">

                        <div class="col-md-12">
                                <hr>
                                <h5 class="text-uppercase bg-light-yellopink p-2 mt-0 mb-3">Multiple Tags</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="typeInput">
                                            {!! Form::label('title', __('Select Geo Fence'),['class' => 'control-label']) !!}
                                            {!! Form::select('geo_id',$geos,null,['class' => 'selectpicker']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="typeInput">
                                            {!! Form::label('title', __('Select Team'),['class' => 'control-label']) !!}
                                            {!! Form::select('team_id',$teams,null,['class' => 'selectpicker']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">    
                                    <div class="col-md-6">
                                        <div class="form-group" id="typeInput">
                                            {!! Form::label('title', __('Select Team Tag'),['class' => 'control-label']) !!}
                                            {!! Form::select('team_tag_id',$team_tag,null,['class' => 'selectpicker']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="typeInput">
                                            {!! Form::label('title', __('Select Driver Tag'),['class' => 'control-label']) !!}
                                            {!! Form::select('driver_tag_id',$driver_tag,null,['class' => 'selectpicker']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="text-uppercase bg-light-yellopink p-2 mt-0 mb-3">Timetable  <span class="digital-clock1" style="float:right;color: rgb(183 33 33);">00:00:00</span></h5>
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>{{__("Start Time")}}</th>
                                                <th>{{__("End Time")}}</th>
                                                <th>{{__("Add")}}</th>   
                                            </tr>
                                        </thead>
                                        <?php $i = 0;?>
                                        @foreach($weekdays as $weekday)<?php $i++;?>
                                        <tbody id="timeframe_tbody_{{$i}}">
                                            <tr id="timeframe_row_{{$i}}_1">
                                                <td>
                                                    <input type="hidden" name="hddnWeekdays_{{$i}}" id="hddnWeekdays_{{$i}}" value="{{$weekday}}" />
                                                    <div class="checkbox checkbox-primary mb-1">
                                                        <input type="checkbox" name="checkdays[]" id="checkdays_{{$i}}" value="1" data-parsley-mincheck="2">
                                                        <label for="checkdays_{{$i}}">&nbsp;&nbsp;&nbsp;&nbsp;{{__($weekday)}} </label>
                                                    </div>
                                                </td>
                                                
                                                <td>{!! Form::text('price_starttime_'.$i.'_1', null, ['id'=>'price_starttime_'.$i.'_1', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('00:00')]) !!}</td>
                                                <td>{!! Form::text('price_endtime_'.$i.'_1', null, ['id'=>'price_endtime_'.$i.'_1', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('00:00')]) !!}</td>
                                                <td style="text-align:center;">
                                                    <input type="hidden" name="no_of_time_{{$i}}" id="no_of_time_{{$i}}" value="1" />
                                                    <button type="button" class="btn btn-info btn-rounded waves-effect waves-light add_sub_pricing_row" data-id="{{$i}}"><i class="far fa-plus-square"></i> Add</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        @endforeach
                                    </table>
                                </div>

                                <hr>
                                <h5 class="text-uppercase bg-light-yellopink p-2 mt-0 mb-3">Variables</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="nameInput">
                                            {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                                            {!! Form::text('name', null, ['class' => 'form-control','placeholder'=> __('Name'),'required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', __('Base Price'),['class' => 'control-label']) !!}
                                            {!! Form::text('base_price', 10, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', __('Base Duration'),['class' => 'control-label']) !!}
                                            {!! Form::text('base_duration', 1, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', __('Base Distance'),['class' => 'control-label']) !!}
                                            {!! Form::text('base_distance', 1, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', __('Duration Price(per minute)'),['class' => 'control-label']) !!}
                                            {!! Form::text('duration_price', 1, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', __('Distance Fee'),['class' => 'control-label']) !!}
                                            {!! Form::text('distance_fee', 1, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', __('Employee Commission Percentage'),['class' => 'control-label']) !!}
                                            {!! Form::text('agent_commission_percentage', null, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', __('Employee Commission Fixed'),['class' => 'control-label']) !!}
                                            {!! Form::text('agent_commission_fixed', null, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', __('Freelancer Commission Percentage'),['class' => 'control-label']) !!}
                                            {!! Form::text('freelancer_commission_percentage', null, ['class' => 'form-control']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', __('Freelancer Commission Fixed'),['class' => 'control-label']) !!}
                                            {!! Form::text('freelancer_commission_fixed', null, ['class' => 'form-control']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                           
                            {{-- Do not Remove this blow div --}}
                            <div class="" id="nestable_list_1" style="display: none">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">{{__("Submit")}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-price-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Pricing Edit")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body px-3 py-0">
                <div class="row">
                    <div class="col-md-12" id="editCardBox">

                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-blue waves-effect waves-light submitEditForm">{{__("Submit")}}</button>
            </div>

        </div>
    </div>
</div>