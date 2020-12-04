<div id="add-pricing-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_customer" action="{{ route('pricing-rules.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card-box">
                                <h4 class="header-title mb-3"></h4>
                                
                    
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="nameInput">
                                            {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                                            {!! Form::text('name', null, ['class' => 'form-control','placeholder'=> 'Name','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Start Date Time',['class' => 'control-label']) !!}
                                            <input type="text" class="form-control datetime-datepicker" placeholder="Date and Time" name="start_date_time" required value="">
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
                                            <input type="text" class="form-control datetime-datepicker" placeholder="Date and Time" name="end_date_time" value="" required>
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group new" id="">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch1" name="is_default" value="y">
                                                <label class="custom-control-label" for="customSwitch1">Turn On For Default Alloction</label>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                    
                                

                                <div class="row temp">
                                    <div class="col-md-6">
                                        <div class="form-group" id="typeInput">
                                            {!! Form::label('title', 'Select Geo Fence',['class' => 'control-label']) !!}
                                            {!! Form::select('geo_id',$geos,null,['class' => 'selectpicker',]) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="typeInput">
                                            {!! Form::label('title', 'Select Team',['class' => 'control-label']) !!}
                                            {!! Form::select('team_id',$teams,null,['class' => 'selectpicker']) !!}
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
                                            {!! Form::select('team_tag_id',$team_tag,null,['class' => 'selectpicker']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="typeInput">
                                            {!! Form::label('title', 'Select Driver Tag',['class' => 'control-label']) !!}
                                            {!! Form::select('driver_tag_id',$driver_tag,null,['class' => 'selectpicker']) !!}
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
                                            {!! Form::text('base_price', null, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Base Duration',['class' => 'control-label']) !!}
                                            {!! Form::text('base_duration', null, ['class' => 'form-control','required' => 'required']) !!}
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
                                            {!! Form::text('base_distance', null, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Base Waiting',['class' => 'control-label']) !!}
                                            {!! Form::text('base_waiting', null, ['class' => 'form-control','required' => 'required']) !!}
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
                                            {!! Form::text('duration_price', null, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Waiting Price',['class' => 'control-label']) !!}
                                            {!! Form::text('waiting_price', null, ['class' => 'form-control','required' => 'required']) !!}
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
                                            {!! Form::text('distance_fee', null, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Cancel Fee',['class' => 'control-label']) !!}
                                            {!! Form::text('cancel_fee', null, ['class' => 'form-control','required' => 'required']) !!}
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
                                            {!! Form::text('agent_commission_percentage', null, ['class' => 'form-control','required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Agent Commission Fixed',['class' => 'control-label']) !!}
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
                                            {!! Form::label('title', 'Freelancer Commission Percentage',['class' => 'control-label']) !!}
                                            {!! Form::text('freelancer_commission_percentage', null, ['class' => 'form-control']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Freelancer Commission Fixed',['class' => 'control-label']) !!}
                                            {!! Form::text('freelancer_commission_fixed', null, ['class' => 'form-control']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Do not Remove this blow div --}}
                            <div class="" id="nestable_list_1" style="display: none">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-price-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-12" id="editCardBox">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-blue waves-effect waves-light submitEditForm">Submit</button>
            </div>
          
        </div>
    </div>
</div>
