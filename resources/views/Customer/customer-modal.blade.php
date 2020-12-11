<div id="add-customer-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_customer" action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card-box">
                                <h4 class="header-title mb-3"></h4>

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
                                        <div class="form-group" id="emailInput">
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
                                        <div class="form-group" id="phone_numberInput">
                                            {!! Form::label('title', 'Phone Number',['class' => 'control-label']) !!}
                                            {!! Form::text('phone_number', null, ['class' => 'form-control']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="addapp"> 
                                    {!! Form::label('title', 'Address',['class' => 'control-label']) !!} 
                                    <div class="row address" id="add1">
                                        <div class="col-md-4">
                                            <div class="form-group" id="short_nameInput"> 
                                                <input type="text" name="short_name[]" class="form-control" placeholder="Short Name">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group input-group" id="addressInput">
                                                <input type="text" id="add1-input" name="address[]" class="form-control" placeholder="Address">
                                                <div class="input-group-append">
                                                    <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="add1"> <i class="mdi mdi-map-marker-radius"></i></button>
                                                </div>
                                                <input type="hidden" name="latitude[]" id="add1-latitude" value="0" />
                                                <input type="hidden" name="longitude[]" id="add1-longitude" value="0" />
                                                <span class="invalid-feedback" role="alert" id="address">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" id="post_codeInput">
                                                <input type="text" name="post_code[]" class="form-control" placeholder="Post Code">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="address-map-container" style="width:100%;height:400px; display: none;">
                                        <div style="width: 100%; height: 100%" id="address-map"></div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-8" id="adds">
                                        <a href="#"  class="btn btn-success btn-rounded waves-effect waves-light addField" >Add More Address</a>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-5">
                                        
                                    </div>
                                    <div class="col-md-7">
                                        
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submitCustomerForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-customer-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="edit_customer" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4" id="editCardBox">
                    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-blue waves-effect waves-light submitEditForm">Submit</button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Select Location</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                
                <div class="row">
                    <form id="task_form" action="#" method="POST" style="width: 100%">
                        <div class="col-md-12">
                            <div id="googleMap" style="height: 500px; min-width: 500px; width:100%"></div>
                            <input type="hidden" name="lat_input" id="lat_map" value="0" />
                            <input type="hidden" name="lng_input" id="lng_map" value="0" />
                            <input type="hidden" name="for" id="map_for" value="" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-blue waves-effect waves-light selectMapLocation">Ok</button>
                <!--<button type="Cancel" class="btn btn-blue waves-effect waves-light cancelMapLocation">cancel</button>-->
            </div>
        </div>
    </div>
</div>