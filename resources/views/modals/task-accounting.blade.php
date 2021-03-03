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
                            <div class="">
                                <h4 class="header-title mb-3">Pay Details</h4>

                                <div class="row">
                                    
                                        <div class="col-md-3">
                                            <div class="form-group pay-detail-box " id="">
                                                {!! Form::label('title', 'Base Price',['class' => 'control-label']) !!} <br>
                                                <span id="base_price"></span>
                                            </div>
                                        </div> 


                                        <div class="col-md-3">
                                            <div class="form-group pay-detail-box " id="">
                                                {!! Form::label('title', 'Duration Price',['class' => 'control-label']) !!} <br>
                                                <span id="duration_price"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group pay-detail-box " id="">
                                                {!! Form::label('title', 'Distance Price',['class' => 'control-label']) !!} <br>
                                                <span id="distance_fee"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group pay-detail-box " id="">
                                                <label class="control-label">{{ Session::get('agent_name') ? Session::get('agent_name') : 'Agent' }} Type</label> <br>
                                                
                                                <span id="driver_type"></span>
                                            </div>
                                        </div>
                                       
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Base Distance',['class' => 'control-label']) !!} <br>
                                            <span id="base_distance"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Actual Distance',['class' => 'control-label']) !!} <br>
                                            <span id="actual_distance"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Billing Distance',['class' => 'control-label']) !!} <br>
                                            <span id="billing_distance"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Distance Cost',['class' => 'control-label']) !!} <br>
                                            <span id="distance_cost"></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Base Duration',['class' => 'control-label']) !!} <br>
                                            <span id="base_duration"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Actual Duration',['class' => 'control-label']) !!} <br>
                                            <span id="actual_duration"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Billing Duration',['class' => 'control-label']) !!} <br>
                                            <span id="billing_duration"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Duration Cost',['class' => 'control-label']) !!} <br>
                                            <span id="duration_cost"></span>
                                        </div>
                                    </div>
                                </div>

                                

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Order Cost',['class' => 'control-label']) !!}
                                            <h5 id="order_cost"></h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Driver Cost',['class' => 'control-label']) !!}
                                            <h5 id="driver_cost"></h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Agent Commission %',['class' => 'control-label']) !!} <br>
                                            <span id="agent_commission_percentage"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id=""> 
                                            {!! Form::label('title', 'Agent Commission Fixed',['class' => 'control-label']) !!} <br>
                                            <span id="agent_commission_fixed"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box " id="">
                                            {!! Form::label('title', 'Freelancer Commission%',['class' => 'control-label']) !!} <br>
                                            <span id="freelancer_commission_percentage"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', 'Freelancer Commission Fixed',['class' => 'control-label']) !!} <br>
                                            <span id="freelancer_commission_fixed"></span>
                                        </div>
                                    </div>
                                </div>
                                
 
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

