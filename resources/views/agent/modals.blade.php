<div id="add-agent-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add {{ Session::get('agent_name') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submitAgent" enctype="multipart/form-data"  action="{{ url('agent/store') }}">
                <div class="modal-body p-4">
                
                    @csrf
                    <input type="hidden" name="country_code" id="countryCode">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="form-group" id="profile_pictureInput">
                                <input type="file" data-plugins="dropify" name="profile_picture" />
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                            <p class="text-muted text-center mt-2 mb-0">Profile Pic</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">NAME</label>
                                <input type="text" class="form-control" id="name" placeholder="John Doe" name="name">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="phone_numberInput">
                                <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                <div class="input-group">
                                    <input type="tel" name="phone_number" class="form-control xyz" id="phone_number" placeholder="9876543210" maxlength="14">
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="typeInput">
                                <label for="type" class="control-label">TYPE</label>
                                <select class="selectpicker" data-style="btn-light" name="type" id="type">
                                    <option value="Employee">Employee</option>
                                    <option value="Freelancer">Freelancer</option>
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="team_idInput">
                                <label for="team_id" class="control-label">ASSIGN TEAM</label>
                                <select class="selectpicker" data-style="btn-light" name="team_id" id="team_id">
                                    @foreach($teams as $team)
                                    <option value="{{$team->id}}">{{$team->name}}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-md-12">
                            <div class="form-group" id="vehicle_type_idInput">
                                <p class="text-muted mt-3 mb-2">TRANSPORT TYPE</p>
                                <div class="radio radio-blue form-check-inline click cursors">
                                    <input type="radio" id="onfoot" value="1" name="vehicle_type_id" act="add" checked>
                                    <img id="foot_add" src="{{asset('assets/icons/walk.png')}}"> 
                                </div>

                                <div class="radio radio-primery form-check-inline click cursors">
                                    <input type="radio" id="bycycle" value="2" name="vehicle_type_id" act="add">
                                    <img id="cycle_add" src="{{asset('assets/icons/cycle.png')}}">
                                </div>
                                <div class="radio radio-info form-check-inline click cursors">
                                    <input type="radio" id="motorbike" value="3" name="vehicle_type_id" act="add">
                                    <img id="bike_add" src="{{asset('assets/icons/bike.png')}}">
                                </div>
                                <div class="radio radio-danger form-check-inline click cursors">
                                    <input type="radio" id="car" value="4" name="vehicle_type_id" act="add">
                                    <img id="cars_add" src="{{asset('assets/icons/car.png')}}">
                                </div>
                                <div class="radio radio-warning form-check-inline click cursors">
                                    <input type="radio" id="truck" value="5" name="vehicle_type_id" act="add">
                                    <img id="trucks_add" src="{{asset('assets/icons/truck.png')}}">
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label>Tag</label>
                                <input id="form-tags-1" name="tags" type="text" value="" class="myTag1">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="make_modelInput">
                                <label for="make_model" class="control-label">TRANSPORT DETAILS</label>
                                <input type="text" class="form-control" id="make_model" placeholder="Year, Make, Model" name="make_model">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="uid_modelInput">
                                <label for="uid_model" class="control-label">UID</label>
                                <input type="text" class="form-control" id="uid" placeholder="897abd" name="uid">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="plate_numberInput">
                                <label for="plate_number" class="control-label">LICENCE PLACE</label>
                                <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="508.KLV">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="colorInput">
                                <label for="color" class="control-label">COLOR</label>
                                <input type="text" class="form-control" id="color" name="color" placeholder="Color">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>

                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submitAgentForm">Submit</button>
                </div>
            </form>
        </div>
        
    </div>
</div>

<div id="edit-agent-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit {{ Session::get('agent_name') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="UpdateAgent" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4" id="editCardBox">
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submitEditForm">Submit</button>
                </div>
                
            
        </div>
    </div>
</div>
</form>
