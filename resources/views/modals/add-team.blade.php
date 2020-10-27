<div id="add-team-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Team</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submitTeam" action="{{ route('team.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">NAME</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="John Doe"
                                    require>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group mb-3" id="manager_idInput">
                                <label for="team-manager">Manager</label>
                                <select class="form-control" id="team-manager" name="manager_id">
                                    @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="location_accuracyInput">
                                <label for="location_accuracy">Location Accuracy</label>
                                <select class="form-control" id="location_accuracy" name="location_accuracy">
                                    @foreach ($location_accuracy as $k => $la)
                                        <option value="{{ $k }}">{{ $la }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="location_frequencyInput">
                                <label for="location_frequency">Location Frequency</label>
                                <select class="form-control" id="location_frequency" name="location_frequency">
                                    @foreach ($location_frequency as $k => $lf)
                                        <option value="{{ $k }}">{{ $lf }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label>Tag</label>

                                <input type="text" class="selectize-close-btn" value="" name="tags" id="teamtag">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <ul class="selectedtag">
                                <li class="tag1" value="tag1">tag1</li>
                                <li class="tag1" value="tag2">tag2</li>
                                <li class="tag1" value="tag3">tag3</li>
                            </ul>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->
