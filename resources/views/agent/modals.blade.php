<div id="add-agent-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Add")}} {{ Session::get('agent_name') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="submitAgent" enctype="multipart/form-data" action="{{ url('agent/store') }}">
                <div class="modal-body px-3 py-0">

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
                            <p class="text-muted text-center mt-2 mb-0">{{__("Profile Pic")}}</p>
                        </div>
                    </div>
                    <span class="show_all_error invalid-feedback"></span>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">{{__("NAME")}}</label>
                                <input type="text" class="form-control" id="name" placeholder="John Doe" name="name">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="phone_numberInput">
                                <label for="phone_number" class="control-label">{{__("CONTACT NUMBER")}}</label>
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
                                <label for="type" class="control-label">{{__("TYPE")}}</label>
                                <select class="selectpicker" data-style="btn-light" name="type" id="type">
                                    <option value="Employee">{{__("Employee")}}</option>
                                    <option value="Freelancer">{{__("Freelancer")}}</option>
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="team_idInput">
                                <label for="team_id" class="control-label">{{__("ASSIGN TEAM")}}</label>
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
                                <p class="text-muted mt-3 mb-2">{{__("TRANSPORT TYPE")}}</p>
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
                                <label>{{__("Tag")}}</label>
                                <input id="form-tags-1" name="tags" type="text" value="" class="myTag1">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="make_modelInput1">
                                <label for="make_model" class="control-label">{{__("TRANSPORT DETAILS")}}</label>
                                <input type="text" class="form-control" id="make_model" placeholder={{__("Year, Make, Model")}} name="make_model">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="uid_modelInput">
                                <label for="uid_model" class="control-label">{{__("UID")}}</label>
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
                                <label for="plate_number" class="control-label">{{__("LICENCE PLACE")}}</label>
                                <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="508.KLV">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="colorInput">
                                <label for="color" class="control-label">{{__("COLOR")}}</label>
                                <input type="text" class="form-control" id="color" name="color" placeholder={{__("Color")}}>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submitAgentForm">{{__("Submit")}}</button>
                </div>
            </form>
        </div>

    </div>
</div>

<div id="edit-agent-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Edit")}} {{ Session::get('agent_name') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="UpdateAgent" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-3" id="editCardBox">

                </div>

                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submitEditForm">{{__("Submit")}}</button>
                </div>


        </div>
    </div>
</div>
</form>

<div id="view-agent-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">View {{ Session::get('agent_name') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
         
                <div class="modal-body px-3" id="viewCardBox">

                </div>

             

        </div>
    </div>
</div>


