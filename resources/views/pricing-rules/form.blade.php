
<form id="edit_price" action="{{ route('pricing-rules.update', $pricing->id) }}" method="POST">
    @csrf
    @method('PUT')
    
        <div class="card-box p-0">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="nameInput">
                        {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                        {!! Form::text('name', $pricing->name, ['class' => 'form-control','placeholder'=> 'Name','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
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
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', __('Base Duration (In Minutes)'),['class' => 'control-label']) !!}
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
                        {!! Form::label('title', __('Base Distance'),['class' => 'control-label']) !!}
                        {{isset($client_pre->distance_unit) && $client_pre->distance_unit == 'metric' ?'(Km)':'(Mile)'}}
                        {!! Form::text('base_distance', $pricing->base_distance, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', __('Duration Price(per minute)'),['class' => 'control-label']) !!}
                        {!! Form::text('duration_price', $pricing->duration_price, ['class' => 'form-control','required' => 'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
            </div>

            <div class="row">
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
                <div class="col-md-6">
                    <div class="form-group" id="">
                        {!! Form::label('title', __('Cancel Fee'),['class' => 'control-label']) !!}
                        {{isset($client_pre->currency)?'('.$client_pre->currency->iso_code.')':''}}
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

        </div>
        {{-- Do not Remove this blow div --}}
        <div class="" id="nestable_list_1" style="display: none">
            
        </div>
    
</form>