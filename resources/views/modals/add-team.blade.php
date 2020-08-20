<div id="add-team-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Team</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="" action="{{ route('team.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">NAME</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="John Doe">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="manager_idInput">
                                <label for="team-manager">Manager</label>
                                <select class="form-control" id="team-manager" name="manager_id">
                                    @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="location_accuracyInput">
                                <label for="location_accuracy" class="control-label">Location Accuracy</label>
                                <input type="number" class="form-control" name="location_accuracy" id="location_accuracy"
                                    >
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="location_frequencyInput">
                                <label for="location_frequency" class="control-label">Location Frequency</label>
                                <input type="number" class="form-control" name="location_frequency" id="location_frequency"
                                    >
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="tagsInput">
                                <label for="tags" class="control-label">ADD TAGS</label>
                                <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple"
                                    data-placeholder="Choose ..." name="tags[]" id="tags">
                                    @foreach($tags as $tag)
                                    <option value="{{$tag->id}}">{{$tag->name}}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->