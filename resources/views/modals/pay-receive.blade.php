<div id="pay-receive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pay/Receive Money</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submitpayreceive" enctype="">
                @csrf
                <div class="row pt-2">
                    <div class="login-form setmodal">
                        <ul class="list-inline">
                        <li class="d-inline-block mr-2">
                            <input type="radio" id="teacher" name="payment_type"  value="1" checked>
                            <label for="teacher"><span class="showspan">Pay</span></label>
                            </li>
                        <li class="d-inline-block mr-2">
                            <input type="radio" id="student"  name="payment_type" value="2">
                            <label for="student"><span class="showspan">Receive</span></label>
                        </li>
                        
                        </ul>
                    </div>
                </div>
                
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Select {{Session::get('agent_name')}}</label>
                                <select name="driver_id" id="selectAgent" class="selectpicker" required>
                                    <option hidden="true"></option>
                                    @foreach ($agents as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option> 
                                    @endforeach
                                
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-2" class="control-label">Amount</label>
                                <input name="amount" type="text" class="form-control" id="field-2"
                                    placeholder="3000" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card-box dispaly-cards">
                            {!! Form::label('title', 'Order Earning',['class' => 'control-label']) !!} <br>
                            <span id="order_earning"></span>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="card-box dispaly-cards">
                            {!! Form::label('title', 'Cash Collected',['class' => 'control-label']) !!} <br>
                            <span id="cash_collected"></span>
                        </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-box dispaly-cards">
                            {!! Form::label('title', 'Final Balance',['class' => 'control-label']) !!} <br>
                            <span id="final_balance"></span>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">Add</button>
                </div>
            </form>    
        </div>
    </div>
</div><!-- /.modal -->