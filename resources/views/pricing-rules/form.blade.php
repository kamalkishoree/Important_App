
<form id="edit_price" action="{{ route('pricing-rules.update', $pricing->id) }}" method="POST">
    @csrf
    @method('PUT')

        <hr>
        <h5 class="text-uppercase bg-light-yellopink p-2 mt-0 mb-3">Multiple Tags</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="typeInput">
                    {!! Form::label('title', __('Select Geo Fence'),['class' => 'control-label']) !!}
                    {!! Form::select('geo_id', $geos, $pricing->geo_id,['class' => 'selectpicker']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="typeInput">
                    {!! Form::label('title', __('Select Team'),['class' => 'control-label']) !!}
                    {!! Form::select('team_id', $teams, $pricing->team_id,['class' => 'selectpicker']) !!}
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
                    {!! Form::select('team_tag_id', $team_tag, $pricing->team_tag_id,['class' => 'selectpicker']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="typeInput">
                    {!! Form::label('title', __('Select Driver Tag'),['class' => 'control-label']) !!}
                    {!! Form::select('driver_tag_id', $driver_tag, $pricing->driver_tag_id,['class' => 'selectpicker']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        <hr>
        <h5 class="text-uppercase bg-light-yellopink p-2 mt-0 mb-3">Timetable  <span class="digital-clock1" style="float:right;color: rgb(183 33 33);text-shadow:0 0 6px #ff0;">00:00:00</span></h5>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>{{__("Start Time")}}</th>
                        <th>{{__("End Time")}}</th>
                        <th>{{__("Add")}} <input type="hidden" id="hddn_edit_days_count" name="hddn_edit_days_count" value="{{count($pricetimeframes)}}"/></th>   
                    </tr>
                </thead>
                <?php $i = 0;?>
                @foreach($pricetimeframes as $pricetimeframe)
                    <?php $i++;?>

                    @if(count($pricetimeframe['timeframe']) > 0)
                        <?php $weekday = $pricetimeframe['days'];$j = 0;?>
                        <tbody id="timeframe_edit_tbody_{{$i}}">
                        @foreach($pricetimeframe['timeframe'] as $timeframe)
                        
                            <?php $j++;?>
                            
                            <tr id="timeframe_edit_row_{{$i}}_{{$j}}">
                                <td>
                                @if($j == 1)
                                <input type="hidden" name="hddnWeekdays_edit_{{$i}}" id="hddnWeekdays_edit_{{$i}}" value="{{$weekday}}" />
                                <div class="checkbox checkbox-primary mb-1">
                                    <input type="checkbox" name="checkdays_edit_{{$i}}" id="checkdays_edit_{{$i}}" value="1" data-parsley-mincheck="2" @if($timeframe['is_applicable'] == 1) checked @endif>
                                    <label for="checkdays_edit_{{$i}}">&nbsp;&nbsp;&nbsp;&nbsp;{{__($weekday)}} </label>
                                </div>
                                @endif
                                </td>
                                
                                <td>{!! Form::text('edit_price_starttime_'.$i.'_'.$j, $timeframe['start_time'], ['id'=>'edit_price_starttime_'.$i.'_'.$j, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('00:00')]) !!}</td>
                                <td>{!! Form::text('edit_price_endtime_'.$i.'_'.$j, $timeframe['end_time'], ['id'=>'edit_price_endtime_'.$i.'_'.$j, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('00:00')]) !!}</td>
                                <td style="text-align:center;">
                                @if($j == 1)
                                    <input type="hidden" name="edit_no_of_time_{{$i}}" id="edit_no_of_time_{{$i}}" value="{{count($pricetimeframe['timeframe'])}}" />
                                    <button type="button" class="btn btn-info btn-rounded waves-effect waves-light add_edit_sub_pricing_row" data-id="{{$i}}"><i class="far fa-plus-square"></i> Add</button>
                                @else
                                    <span data-id="pricruledelspan_{{$i}}_{{$j}}" class="del_edit_pricrule_span"><img style="filter: grayscale(.5);" src="{{asset("assets/images/ic_delete.png")}}"  alt=""></span>
                                @endif
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    @else

                    <tbody id="timeframe_edit_tbody_{{$i}}">
                        <tr id="timeframe_edit_row_{{$i}}_1">
                            <td>
                                <input type="hidden" name="hddnWeekdays_edit_{{$i}}" id="hddnWeekdays_edit_{{$i}}" value="{{$pricetimeframe['days']}}" />
                                <div class="checkbox checkbox-primary mb-1">
                                    <input type="checkbox" name="checkdays_edit_{{$i}}" id="checkdays_edit_{{$i}}" value="1" data-parsley-mincheck="2">
                                    <label for="checkdays_edit_{{$i}}">&nbsp;&nbsp;&nbsp;&nbsp;{{__($pricetimeframe['days'])}} </label>
                                </div>
                            </td>
                            
                            <td>{!! Form::text('edit_price_starttime_'.$i.'_1', null, ['id'=>'edit_price_starttime_'.$i.'_1', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('00:00')]) !!}</td>
                            <td>{!! Form::text('edit_price_endtime_'.$i.'_1', null, ['id'=>'edit_price_endtime_'.$i.'_1', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('00:00')]) !!}</td>
                            <td style="text-align:center;">
                                <input type="hidden" name="edit_no_of_time_{{$i}}" id="edit_no_of_time_{{$i}}" value="1" />
                                <button type="button" class="btn btn-info btn-rounded waves-effect waves-light add_edit_sub_pricing_row" data-id="{{$i}}"><i class="far fa-plus-square"></i> Add</button>
                            </td>
                        </tr>
                    </tbody>
                    @endif

                @endforeach
                
            </table>
        </div>
         
        <hr>
        <h5 class="text-uppercase bg-light-yellopink p-2 mt-0 mb-3">Variables</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="nameInput">
                    {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                    {!! Form::text('name', $pricing->name, ['class' => 'form-control','placeholder'=> 'Name','required' => 'required']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="">
                    
                    {!! Form::label('title', __('Base Price'),['class' => 'control-label']) !!}
                    {{isset($client_pre->currency)?'('.$client_pre->currency->iso_code.')':''}}
                    {!! Form::text('base_price', $pricing->base_price, ['class' => 'form-control','required' => 'required']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="">
                    {!! Form::label('title', __('Base Duration (In Minutes)'),['class' => 'control-label']) !!}
                    {!! Form::text('base_duration', $pricing->base_duration, ['class' => 'form-control','required' => 'required']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="">
                    {!! Form::label('title', __('Base Distance'),['class' => 'control-label']) !!}
                    {{isset($client_pre->distance_unit) && $client_pre->distance_unit == 'metric' ?'(Km)':'(Mile)'}}
                    {!! Form::text('base_distance', $pricing->base_distance, ['class' => 'form-control','required' => 'required']) !!}
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
                    {!! Form::text('duration_price', $pricing->duration_price, ['class' => 'form-control','required' => 'required']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="">
                    {!! Form::label('title', __('Distance Fee'),['class' => 'control-label']) !!}
                    {{isset($client_pre->currency)?'('.$client_pre->currency->iso_code.')':''}}
                    {!! Form::text('distance_fee', $pricing->distance_fee, ['class' => 'form-control','required' => 'required']) !!}
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
                    {!! Form::text('agent_commission_percentage', $pricing->agent_commission_percentage, ['class' => 'form-control','required' => 'required']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="">
                    {!! Form::label('title', __('Employee Commission Fixed'),['class' => 'control-label']) !!}
                    {!! Form::text('agent_commission_fixed', $pricing->agent_commission_fixed, ['class' => 'form-control','required' => 'required']) !!}
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
                    {!! Form::text('freelancer_commission_percentage', $pricing->freelancer_commission_percentage, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="">
                    {!! Form::label('title', __('Freelancer Commission Fixed'),['class' => 'control-label']) !!}
                    {!! Form::text('freelancer_commission_fixed', $pricing->freelancer_commission_fixed, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        
        
        <div class="row">                
            <div class="col-md-12">
                <button type="submit" class="btn btn-blue waves-effect waves-light" style="display: none">{{__('Submit')}}</button>
            </div>
        </div>
        {{-- Do not Remove this blow div --}}
        <div class="" id="nestable_list_1" style="display: none">
            
        </div>
    
</form>