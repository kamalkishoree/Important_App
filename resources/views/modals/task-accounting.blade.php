<div id="task-accounting-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_customer" action="" method="">
                @csrf
                <div class="modal-body p-4">
                    
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card-box">
                                <h4 class="header-title mb-3"></h4>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Base Distance',['class' => 'control-label']) !!}
                                            {!! Form::text('base_distance', null, ['class' => 'form-control','required' => 'required','id' => 'base_distance']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Actual Distance',['class' => 'control-label']) !!}
                                            {!! Form::text('actual_distance', null, ['class' => 'form-control','id' => 'actual_distance']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Billing Distance',['class' => 'control-label']) !!}
                                            {!! Form::text('billing_distance', null, ['class' => 'form-control','required' => 'required','id' => 'billing_distance']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Distance Cost',['class' => 'control-label']) !!}
                                            {!! Form::text('distance_cost', null, ['class' => 'form-control','required' => 'required','id' => 'distance_cost']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Actual Distance',['class' => 'control-label']) !!}
                                            {!! Form::text('actual_distance', null, ['class' => 'form-control','id' => 'actual_distance']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Actual Distance',['class' => 'control-label']) !!}
                                            {!! Form::text('actual_distance', null, ['class' => 'form-control','id' => 'actual_distance']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div> --}}
                                    
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Base Duration',['class' => 'control-label']) !!}
                                            {!! Form::text('base_duration', null, ['class' => 'form-control','required' => 'required','id' => 'base_duration']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Actual Duration',['class' => 'control-label']) !!}
                                            {!! Form::text('actual_duration', null, ['class' => 'form-control','required' => 'required','id' => 'actual_duration']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Billing Duration',['class' => 'control-label']) !!}
                                            {!! Form::text('billing_duration', null, ['class' => 'form-control','required' => 'required','id' => 'billing_duration']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Duration Cost',['class' => 'control-label']) !!}
                                            {!! Form::text('duration_cost', null, ['class' => 'form-control','required' => 'required','id' => 'duration_cost']) !!}
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
                                            {!! Form::text('duration_price', null, ['class' => 'form-control','required' => 'required','id' => 'duration_price']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Waiting Price',['class' => 'control-label']) !!}
                                            {!! Form::text('waiting_price', null, ['class' => 'form-control','required' => 'required','id' => 'waiting_price']) !!}
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
                                            {!! Form::text('distance_fee', null, ['class' => 'form-control','required' => 'required','id' => 'distance_fee']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Cancel Fee',['class' => 'control-label']) !!}
                                            {!! Form::text('cancel_fee', null, ['class' => 'form-control','required' => 'required','id' => 'cancel_fee']) !!}
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
                                            {!! Form::text('agent_commission_percentage', null, ['class' => 'form-control','required' => 'required','id' => 'agent_commission_percentage']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Agent Commission Fixed',['class' => 'control-label']) !!}
                                            {!! Form::text('agent_commission_fixed', null, ['class' => 'form-control','required' => 'required','id' => 'agent_commission_fixed']) !!}
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
                                            {!! Form::text('freelancer_commission_percentage', null, ['class' => 'form-control','id' => 'freelancer_commission_percentage']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Freelancer Commission Fixed',['class' => 'control-label']) !!}
                                            {!! Form::text('freelancer_commission_fixed', null, ['class' => 'form-control','id' => 'freelancer_commission_fixed']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'actual_time',['class' => 'control-label']) !!}
                                            {!! Form::text('actual_time', null, ['class' => 'form-control','id' => 'actual_time']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Actual Distance',['class' => 'control-label']) !!}
                                            {!! Form::text('actual_distance', null, ['class' => 'form-control','id' => 'actual_distance']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Order Cost',['class' => 'control-label']) !!}
                                            {!! Form::text('order_cost', null, ['class' => 'form-control','id' => 'order_cost']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Driver Cost',['class' => 'control-label']) !!}
                                            {!! Form::text('driver_cost', null, ['class' => 'form-control','id' => 'driver_cost']) !!}
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
                {{-- <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">Submit</button>
                </div> --}}
            </form>
        </div>
    </div>
</div>

