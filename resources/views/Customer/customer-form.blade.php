
<div class="row">

    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3"></h4>
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="nameInput">
                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::label('title', 'Email',['class' => 'control-label']) !!}
                        {!! Form::email('email', null, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::label('title', 'Phone Number',['class' => 'control-label']) !!}
                        {!! Form::text('phone_number', null, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>
            <div>
             <div class="addapp"> 
                {!! Form::label('title', 'Address',['class' => 'control-label']) !!} 
                <div class="row address">
                    <div class="col-md-4">
                        <div class="form-group" id=""> 
                            <input type="text" name="short_name[]" class="form-control" placeholder="Short Name">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="">
                            <input type="text" name="address[]" class="form-control" placeholder="Address">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="">
                            <input type="text" name="post_code[]" class="form-control" placeholder="Post Code">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
             </div>
             <div class="row">
                <div class="col-md-4">

                </div>
                <div class="col-md-8" id="adds">
                    <a href="#"  class="btn btn-success btn-rounded waves-effect waves-light" >Add More Address</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <button type="submit" class="btn btn-blue waves-effect waves-light ">Submit</button>
                </div>
                <div class="col-md-7">
                    
                </div>
            </div>



        </div>
    </div>


</div>