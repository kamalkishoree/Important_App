
 <div class="row mb-2">
    <div class="col-md-4">
        <div class="form-group" id="profile_pictureInputEdit">
            <input type="file" id="profilePic" data-plugins="dropify" name="profile_picture" data-default-file="" showImg="{{ isset($agent->profile_picture) ? Storage::disk('s3')->url($agent->profile_picture) : '' }}" />
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
        <p class="text-muted text-center mt-2 mb-0">Profile Pic</p>
    </div>
    <div class="offset-md-2 col_md-6">
        <span>Live OTP</span>
        <h4>{{isset($otp)?$otp:'View OTP after Logging in the Driver App'}}</h4>
    </div>
    
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="nameInputEdit">
            <label for="name" class="control-label">NAME</label>
            <input type="text" class="form-control" id="name" placeholder="John Doe" name="name"
                value="{{ old('name', $agent->name ?? '') }}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="phone_numberInputEdit">
            <label for="phone_number" class="control-label">CONTACT NUMBER</label>
            <div class="input-group">
               
                <input type="tel" name="phone_number" class="form-control xyz" id="phone"
                    placeholder="Enter mobile number"
                    value="{{ $agent->phone_number }}" >
                    {{-- <input id="phone" name="phone" type="tel" class="form-control"> --}}
            </div>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="typeInputEdit">
            <label for="type" class="control-label">TYPE</label>
            <select class="form-control" data-style="btn-light" name="type" id="type">
                <option value="Employee" @if ($agent->type == 'Employee') selected @endif
                    >Employee</option>
                <option value="Freelancer" @if ($agent->type == 'Freelancer') selected @endif
                    >Freelancer</option>

            </select>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="team_idInputEdit">
            <label for="team_id" class="control-label">ASSIGN TEAM</label>
            <select class="form-control" data-style="btn-light" name="team_id" id="team_id">
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}" {{$team->id == $agent->team_id ? 'selected':''}}>{{ $team->name }}</option>
                @endforeach
                
            </select>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <div class="form-group" id="vehicle_type_idInputEdit">
            <p class="text-muted mt-3 mb-2">TRANSPORT TYPE</p>
            <div class="radio radio-blue form-check-inline click cursors">
                <input type="radio" id="onfoot" value="1" act="edit" name="vehicle_type_id" @if ($agent->vehicle_type_id == '1') checked
                @endif>
                <img id="foot_edit" src="{{ $agent->vehicle_type_id == '1' ? asset('assets/icons/walk_blue.png') : asset('assets/icons/walk.png') }}">
            </div>

            <div class="radio radio-primery form-check-inline click cursors">
                <input type="radio" id="bycycle" value="2" name="vehicle_type_id" act="edit" @if ($agent->vehicle_type_id == '2')
                checked @endif >
                <img id="cycle_edit" src="{{ $agent->vehicle_type_id == '2' ? asset('assets/icons/cycle_blue.png') : asset('assets/icons/cycle.png') }}">
            </div>
            <div class="radio radio-info form-check-inline click cursors">
                <input type="radio" id="motorbike" value="3" name="vehicle_type_id" act="edit" @if ($agent->vehicle_type_id == '3') checked @endif>
                <img id="bike_edit" src="{{ $agent->vehicle_type_id == '3' ? asset('assets/icons/bike_blue.png') : asset('assets/icons/bike.png') }}">
            </div>
            <div class="radio radio-danger form-check-inline click cursors">
                <input type="radio" id="car" value="4" name="vehicle_type_id" act="edit" @if ($agent->vehicle_type_id == '4') checked
                @endif>
                <img id="cars_edit" src="{{ $agent->vehicle_type_id == '4' ? asset('assets/icons/car_blue.png') : asset('assets/icons/car.png') }}">
            </div>
            <div class="radio radio-warning form-check-inline click cursors">
                <input type="radio" id="truck" value="5" name="vehicle_type_id" act="edit" @if ($agent->vehicle_type_id == '5') checked @endif>
                <img id="trucks_edit"
                    src="{{ $agent->vehicle_type_id == '5' ? asset('assets/icons/truck_blue.png') : asset('assets/icons/truck.png') }}">
            </div>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-0">
            <label class="control-label">Tags</label>
        <input id="form-tags-4" name="tags" type="text" value="{{isset($tagIds) ? implode(',', $tagIds) : ''}}" class="myTag1">
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="form-group" id="make_modelInputEdit">
            <input type="hidden" id="agent_id" val_id="{{ $agent->id }}" url="{{route('agent.update', $agent->id)}}">
            <label for="make_model" class="control-label">TRANSPORT DETAILS</label>
            <input type="text" class="form-control" id="make_model"
                placeholder="Year, Make, Model" name="make_model"
                value="{{ old('name', $agent->make_model ?? '') }}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="make_modelInput1">
            <label for="make_model" class="control-label">UID</label>
            <input type="text" class="form-control" id="uid" placeholder="897abd" name="uid" value="{{ $agent->uid}}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="plate_numberInputEdit">
            <label for="plate_number" class="control-label">LICENCE PLACE</label>
            <input type="text" class="form-control" id="plate_number" name="plate_number"
                placeholder="508.KLV" value="{{ old('name', $agent->plate_number ?? '') }}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="colorInputEdit">
            <label for="color" class="control-label">COLOR</label>
            <input type="text" class="form-control" id="color" name="color" placeholder="Color" value="{{ old('name', $agent->color ?? '') }}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>