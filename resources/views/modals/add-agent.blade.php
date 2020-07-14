<div id="add-agent-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Agent</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <img src="{{asset('assets/images/users/user-3.jpg')}}"
                            class="rounded-circle img-thumbnail avatar-xl" alt="profile-image">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-1" class="control-label">NAME</label>
                            <input type="text" class="form-control" id="field-1" placeholder="John Doe">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-2" class="control-label">CONTACT NUMBER</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">+91</span>
                                </div>
                                <input type="text" class="form-control" id="field-2" placeholder="Enter mobile number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">TYPE</label>
                            <select class="selectpicker" data-style="btn-light">
                                <option>Freelancer</option>
                                <option>Employee</option>
                                <option>Relish</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">ASSIGN TEAM</label>
                            <select class="selectpicker" data-style="btn-light">
                                <option>Chandigarh</option>
                                <option>Mohali</option>
                                <option>USA</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <p class="text-muted mt-3 mb-2">TRANSPORT TYPE</p>
                            <div class="radio radio-primary form-check-inline">
                                <input type="radio" id="onfoot" value="onfoot" name="radioInline" checked>
                                <label for="onfoot"> On Foot </label>
                            </div>
                            <div class="radio radio-success form-check-inline">
                                <input type="radio" id="bycycle" value="bycycle" name="radioInline">
                                <label for="bycycle"> Bycycle </label>
                            </div>
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" id="motorbike" value="motorbike" name="radioInline">
                                <label for="motorbike"> Motor Bike </label>
                            </div>
                            <div class="radio radio-danger form-check-inline">
                                <input type="radio" id="car" value="car" name="radioInline">
                                <label for="car"> Car </label>
                            </div>
                            <div class="radio radio-warning form-check-inline">
                                <input type="radio" id="truck" value="truck" name="radioInline">
                                <label for="truck"> Truck </label>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-3" class="control-label">TRANSPORT DETAILS</label>
                            <input type="text" class="form-control" id="field-3" placeholder="Year, Make, Model">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">LICENCE PLACE</label>
                            <input type="text" class="form-control" id="field-3" placeholder="508.KLV">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">COLOR</label>
                            <input type="text" class="form-control" id="field-3" placeholder="Color">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect waves-light">Add</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->