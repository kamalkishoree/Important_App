@php
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-group" id="profile_pictureInputEdit">
            <input type="file" id="profilePic" data-plugins="dropify" name="profile_picture" data-default-file="" showImg="{{ isset($agent->profile_picture) ? Storage::disk('s3')->url($agent->profile_picture) : '' }}" readonly />
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
<span class="show_all_error invalid-feedback"></span>
<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="nameInputEdit">
            <label for="name" class="control-label">NAME</label>
            <input type="text" class="form-control" id="name" placeholder="John Doe" name="name" value="{{ old('name', $agent->name ?? '') }}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="phone_numberInputEdit">
            <label for="phone_number" class="control-label">CONTACT NUMBER</label>
            <div class="input-group">

                <input type="tel" name="phone_number" class="form-control xyz" id="phone" placeholder="Enter mobile number" value="{{ $agent->phone_number }}" readonly>
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
            <select class="form-control" data-style="btn-light" name="type" id="type" disabled>
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
            <select class="form-control" data-style="btn-light" name="team_id" id="team_id" disabled>
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
                <input type="radio" id="onfoot" value="1" act="edit" name="vehicle_type_id"  readonly="readonly" @if ($agent->vehicle_type_id == '1') checked @else disabled='disabled'
                @endif >
                <img id="foot_edit" src="{{ $agent->vehicle_type_id == '1' ? asset('assets/icons/walk_blue.png') : asset('assets/icons/walk.png') }}">
            </div>
            <div class="radio radio-primery form-check-inline click cursors">
                <input type="radio" id="bycycle" value="2" name="vehicle_type_id" act="edit"  readonly="readonly" @if ($agent->vehicle_type_id == '2')
                checked @else disabled='disabled' @endif  >
                <img id="cycle_edit" src="{{ $agent->vehicle_type_id == '2' ? asset('assets/icons/cycle_blue.png') : asset('assets/icons/cycle.png') }}">
            </div>
            <div class="radio radio-info form-check-inline click cursors">
                <input type="radio" id="motorbike" value="3" name="vehicle_type_id" act="edit" readonly="readonly" @if ($agent->vehicle_type_id == '3') checked @else disabled='disabled' @endif>
                <img id="bike_edit" src="{{ $agent->vehicle_type_id == '3' ? asset('assets/icons/bike_blue.png') : asset('assets/icons/bike.png') }}">
            </div>
            <div class="radio radio-danger form-check-inline click cursors">
                <input type="radio" id="car" value="4" name="vehicle_type_id" act="edit"   readonly="readonly" @if ($agent->vehicle_type_id == '4') checked @else disabled='disabled'
                @endif>
                <img id="cars_edit" src="{{ $agent->vehicle_type_id == '4' ? asset('assets/icons/car_blue.png') : asset('assets/icons/car.png') }}">
            </div>
            <div class="radio radio-warning form-check-inline click cursors">
                <input type="radio" id="truck" value="5" name="vehicle_type_id" act="edit" readonly="readonly" @if ($agent->vehicle_type_id == '5') checked @else disabled='disabled'  @endif>
                <img id="trucks_edit" src="{{ $agent->vehicle_type_id == '5' ? asset('assets/icons/truck_blue.png') : asset('assets/icons/truck.png') }}">
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
            <input id="form-tags-4" name="tags" type="text" value="{{isset($tagIds) ? implode(',', $tagIds) : ''}}" class="myTag1" readonly>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6">
        <div class="form-group" id="make_modelInputEdit">
            <input type="hidden" id="agent_id" val_id="{{ $agent->id }}" url="{{route('agent.update', $agent->id)}}">
            <label for="make_model" class="control-label">TRANSPORT DETAILS</label>
            <input type="text" class="form-control" id="make_model" placeholder="Year, Make, Model" name="make_model" value="{{ old('name', $agent->make_model ?? '') }}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="make_modelInput1">
            <label for="make_model" class="control-label">UID</label>
            <input type="text" class="form-control" id="uid" placeholder="897abd" name="uid" value="{{ $agent->uid}}" readonly>
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
            <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="508.KLV" value="{{ old('name', $agent->plate_number ?? '') }}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="colorInputEdit">
            <label for="color" class="control-label">COLOR</label>
            <input type="text" class="form-control" id="color" name="color" placeholder="Color" value="{{ old('name', $agent->color ?? '') }}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="row">
        <label for="" class="control-label"></label>

        @foreach($agent_docs as $agent_doc)
        <div class="col-md-6">

            @if(strtolower($agent_doc->file_type) == 'text')
            <div class="form-group">
                <label for="" class="control-label">{{$agent_doc->file_name}}</label>

                <input type="text" class="form-control" id="" name="file" placeholder="Enter Text" value="{{ old('name', $agent_doc->file_name ?? '') }}">
            </div>
            @elseif(strtolower($agent_doc->file_type) == 'pdf')
            <div class="form-group">
                <label for="" class="control-label">{{$agent_doc->label_name}}</label>
                <div class="file file--upload">
                    <label for="">
                        <span class="update_pic pdf-icon">
                            <a href="{{Storage::disk('s3')->url($agent_doc->file_name)}}" target="_blank"><img showImg="{{ isset($agent_doc->file_name) ? Storage::disk('s3')->url($agent_doc->file_name) : '' }}" id="file"></a>
                        </span>
                    </label>
                    <div class="invalid-feedback" id=""><strong></strong></div>
                </div>
            </div>
            @else
            <div class="form-group">
                <label for="" class="control-label">{{$agent_doc->label_name}}</label>
                <div class="file file--upload">

                    <a href="{{Storage::disk('s3')->url($agent_doc->file_name)}}" target="_blank"><img src="{{isset($agent_doc->file_name) ? $imgproxyurl.Storage::disk('s3')->url($agent_doc->file_name) : Phumbor::url(URL::to('/asset/images/no-image.png')) }}" style="width:240px;height:120px;"></a>
                    <!-- @if(strtolower($agent_doc->file_type) == 'image')
                <input id="" type="file" name="file" v accept="image/*" data-rel="">
                @elseif(strtolower($agent_doc->file_type) == 'pdf')
                <input id="" type="file" name="file" accept=".pdf" data-rel="">
                @endif -->
                    <div class="invalid-feedback" id=""><strong></strong></div>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>