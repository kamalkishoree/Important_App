
@extends('layouts.vertical', ['title' =>  'Managers' ])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                @if(isset($subadmin))
                <h4 class="page-title">Update Manager</h4>
                @else
                <h4 class="page-title">Create Manager</h4>
                @endif
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($subadmin))
                    <form id="UpdateSubadmin" method="post" action="{{route('subadmins.update', $subadmin->id)}}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @else
                        <form id="StoreSubadmin" method="post" action="{{route('subadmins.store')}}"
                            enctype="multipart/form-data">
                            @endif
                            @csrf
                            {{-- <div class="row mb-2">
                                <div class="col-md-4">
                                    <input type="file" data-plugins="dropify" name="logo"
                                        data-default-file="{{isset($client->logo) ? Storage::disk('s3')->url($client->logo) : ''}}" />
                                    <p class="text-muted text-center mt-2 mb-0">Upload Logo</p>
                                </div>
                            </div> --}}

                            <div class=" row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">NAME</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', $subadmin->name ?? '')}}" placeholder="John Doe" required>
                                        @if($errors->has('name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="control-label">EMAIL</label>
                                        {{-- <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', $subadmin->email ?? '')}}" <?=(isset($subadmin))?"readonly":"";?>
                                            placeholder="Enter email address" required> --}}
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', $subadmin->email ?? '')}}" placeholder="Enter email address" required>
                                        @if($errors->has('email'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">+91</span>
                                            </div>
                                            <input type="text" class="form-control" name="phone_number"
                                                id="phone_number"
                                                value="{{ old('phone_number', $subadmin->phone_number ?? '')}}"
                                                placeholder="Enter mobile number" required>
                                        </div>
                                        @if($errors->has('phone_number'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('phone_number') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="control-label">PASSWORD</label>
                                        @if(isset($subadmin))
                                        {{-- <input type="text" class="form-control" id="password" name="password"
                                            value="{{ old('password', isset($subadmin->confirm_password)?Crypt::decryptString($subadmin->confirm_password) :'********')}}"
                                            placeholder="Enter password"> --}}
                                        <input type="password" class="form-control" id="password" name="password" value="" placeholder="Enter new password(if you want to update)">    
                                        @else
                                        <input type="password" class="form-control" id="password" name="password" value="" placeholder="Enter password" required>
                                        @endif
                                        @if($errors->has('password'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="team_access" class="control-label">Team Access</label>
                                        <?php $teamaccess =  (isset($subadmin))?$subadmin->all_team_access:'';?>
                                        <select name="all_team_access" class="form-control" id="team_access">
                                            <option value="0" <?=($teamaccess==0)?'selected':'';?>>Selected Teams</option>
                                            <option value="1" <?=($teamaccess==1)?'selected':'';?>>All Teams</option>
                                        </select>
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="control-label">Status</label>
                                        <?php $status =  (isset($subadmin))?$subadmin->status:'';?>
                                        <select name="status" class="form-control">
                                            <option value="3" <?=($status==3)?'selected':'';?>>Inactive</option>
                                            <option value="1" <?=($status==1)?'selected':'';?>>Active</option>
                                        </select>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-lg-0 mb-3 user_perm_section table-responsive">
                                    @php
                                        $userpermissions = [];
                                        if(isset($user_permissions))
                                        {
                                            foreach ($user_permissions as $singlepermission) {
                                                $userpermissions[] = $singlepermission->permission_id;
                                            }
                                        }
                                    @endphp
                                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">
        
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Permission Name</th>
                                                <th>Status</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            @foreach($permissions as $singlepermission)
                                            <tr>
                                                <td>
                                                    <h5 class="m-0 font-weight-normal">{{ $singlepermission->name }}</h5>
                                                </td>
        
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input event_type" data-id="{{ $singlepermission->id }}" data-event-type="permission" id="permission_{{ $singlepermission->id}}" name="permissions[]" value="{{ $singlepermission->id }}" @if(in_array($singlepermission->id, $userpermissions)) checked @endif >
                                                        
                                                        <label class="custom-control-label" for="permission_{{ $singlepermission->id}}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
        
                                        </tbody>
                                    </table>
                                </div>
                                <?php $style = (isset($subadmin) && ($subadmin->all_team_access==1))?"none":""; ?>
                                
                                <div class="col-lg-6 team_perm_section table-responsive" style="display:{{$style}}">
                                    @php
                                        $teampermissions = [];
                                        if(isset($team_permissions))
                                        {
                                            foreach ($team_permissions as $singlepermission) {
                                                $teampermissions[] = $singlepermission->team_id;
                                            }
                                        }
                                    @endphp
                                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">
        
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Team Name</th>
                                                <th>Status</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            @foreach($teams as $singleteam)
                                            <tr>
                                                <td>
                                                    <h5 class="m-0 font-weight-normal">{{ $singleteam->name }}</h5>
                                                </td>
        
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input team_permission_check" data-id="{{ $singleteam->id }}" data-event-type="team_permission" id="team_permission_{{ $singleteam->id}}" name="team_permissions[]" value="{{ $singleteam->id }}" @if(in_array($singleteam->id, $teampermissions)) checked @endif >
                                                        <label class="custom-control-label" for="team_permission_{{ $singleteam->id}}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                                    
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                                </div>

                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')

    <script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
    <script src="{{ asset('assets/js/storeAgent.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    {{-- <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>  --}}
    <script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
    <script src="{{ asset('telinput/js/intlTelInput.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />>


<script>

//for handling team access permission
$('#team_access').on('change', function() {
        var value = this.value;
        if(value==1)    //all team selected
        {
            $('.team_perm_section').css('display','none');
            $('.team_permission_check').prop('checked', false);

        }else{      //selected team case
            $('.team_perm_section').css('display','block');
        }
    });

    
</script>
@endsection