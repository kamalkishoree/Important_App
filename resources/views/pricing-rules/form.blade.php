
<form id="edit_price" action="{{ route('pricing-rules.update', $pricing->id) }}" method="POST">
    @csrf
    @method('PUT')
    
        <div class="card-box">
            <h4 class="header-title mb-3"></h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="nameInput">
                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                        {!! Form::text('name', $pricing->name, ['class' => 'form-control','placeholder'=> 'Name','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Start Date Time',['class' => 'control-label']) !!}
                        <input type="datetime-local"  class="form-control datetime-datepicker" placeholder="Date and Time" name="start_date_time" required value="{{isset($pricing) ? date('Y-m-d H:i', strtotime($pricing->start_date_time)) : ''}}">
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'End End Time',['class' => 'control-label']) !!}
                        <input type="datetime-local"  class="form-control datetime-datepicker" placeholder="Date and Time" name="end_date_time" value="{{isset($pricing) ? date('Y-m-d H:i', strtotime($pricing->end_date_time)) : ''}}"required>
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group new" id="">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch2" name="is_default" value="y" {{ $pricing->is_default == 'y' ? 'checked' : ''}}>
                            <label class="custom-control-label" for="customSwitch2">Turn On For Default Alloction</label>
                        </div>
                    </div>
                </div>
               
            </div>

            <div class="row temp">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        {!! Form::label('title', 'Select Geo Fence',['class' => 'control-label']) !!}
                        {!! Form::select('geo_id',$geos,$pricing->geo_id,['class' => 'selectpicker',]) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        {!! Form::label('title', 'Select Team',['class' => 'control-label']) !!}
                        {!! Form::select('team_id',$teams,$pricing->team_id,['class' => 'selectpicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row temp">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        {!! Form::label('title', 'Select Team Tag',['class' => 'control-label']) !!}
                        {!! Form::select('team_tag_id',$team_tag,$pricing->team_tag_id,['class' => 'selectpicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        {!! Form::label('title', 'Select Driver Tag',['class' => 'control-label']) !!}
                        {!! Form::select('driver_tag_id',$driver_tag,$pricing->driver_tag_id,['class' => 'selectpicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Base Price',['class' => 'control-label']) !!}
                        {!! Form::text('base_price', $pricing->base_price, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Base Duration',['class' => 'control-label']) !!}
                        {!! Form::text('base_duration', $pricing->base_duration, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Base Distance',['class' => 'control-label']) !!}
                        {!! Form::text('base_distance', $pricing->base_distance, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Base Waiting',['class' => 'control-label']) !!}
                        {!! Form::text('base_waiting', $pricing->base_waiting, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Duration Price(per minute)',['class' => 'control-label']) !!}
                        {!! Form::text('duration_price', $pricing->duration_price, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Waiting Price',['class' => 'control-label']) !!}
                        {!! Form::text('waiting_price', $pricing->waiting_price, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Distance Fee',['class' => 'control-label']) !!}
                        {!! Form::text('distance_fee', $pricing->distance_fee, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Cancel Fee',['class' => 'control-label']) !!}
                        {!! Form::text('cancel_fee', $pricing->cancel_fee, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Agent Commission Percentage',['class' => 'control-label']) !!}
                        {!! Form::text('agent_commission_percentage', $pricing->agent_commission_percentage, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Agent Commission Fixed',['class' => 'control-label']) !!}
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
                        {!! Form::label('title', 'Freelancer Commission Percentage',['class' => 'control-label']) !!}
                        {!! Form::text('freelancer_commission_percentage', $pricing->freelancer_commission_percentage, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', 'Freelancer Commission Fixed',['class' => 'control-label']) !!}
                        {!! Form::text('freelancer_commission_fixed', $pricing->freelancer_commission_fixed, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
            </div>

            

            
            <div class="row">
                
                <div class="col-md-12">
                    <button type="submit" class="btn btn-blue waves-effect waves-light" style="display: none">Submit</button>
                </div>
            </div>



        </div>
        {{-- Do not Remove this blow div --}}
        <div class="" id="nestable_list_1" style="display: none">
            
        </div>
    
</form>