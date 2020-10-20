@csrf
<div class="row">

    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3">Add Task</h4>
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="nameInput">
                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        {{-- <label for="name" class="control-label">NAME</label>
                        <input type="text" class="form-control" id="name" placeholder="John Doe" name="name"> --}}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::label('title', 'From Address',['class' => 'control-label']) !!}
                        {!! Form::text('from_address', null, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::label('title', 'To Address',['class' => 'control-label']) !!}
                        {!! Form::text('to_address', null, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="typeInput">
                        {!! Form::label('title', 'Status',['class' => 'control-label']) !!}
                        {!! Form::select('status',['Unassigned' => 'Unassigned','assigned' => 'Assigned'],null,['class' => 'selectpicker']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="typeInput">
                        <label for="type" class="control-label">Priority</label>
                        <select class="selectpicker" data-style="btn-light" name="priority" id="type">
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                        </select>
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group" id="colorInput">

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Expected Delivery Date',['class' => 'control-label']) !!}
                            {!! Form::text('expected_delivery_date', null, ['class' => 'form-control','id'=>'humanfd-datepicker']) !!}  
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-5">

                </div>
                <div class="col-md-7">
                    <button type="submit" class="btn btn-blue waves-effect waves-light ">Submit</button>
                </div>
            </div>



        </div>
    </div>


</div>