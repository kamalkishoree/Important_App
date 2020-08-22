
@extends('layouts.vertical', ['title' => 'Notifications'])

@section('css')
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Settings</h4>
                </div>
            </div>
        </div>     
        
        <div class="row">
            <div class="col-xl-12">
                <div class="card-box">
                    <h4 class="header-title">Notifications</h4>
                    <p class="sub-header">
                        Send custom SMS's,emails and webhooks based on each trigger and customize the content by clicking on the pencil icon.         
                    </p>
                    @foreach($notification_types as $notification_type)
                    <div class="card-box">
                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <div class="text-sm-left">
                                    <h4 class="header-title">{{ $notification_type->name }}</h4>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <p class="btn btn-danger waves-effect waves-light text-sm-right">
                                    <i class="mdi mdi-plus-circle mr-1"></i> Add More
                                </p>
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>Events</th>
                                        <th>SMS</th>
                                        <th>EMAIL</th>
                                        <th>WEBHOOK</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notification_type->notification_events as $event)
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{ $event->name }}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="smscustomSwitch1" @if($event->is_checked_sms(auth()->user()->id))  checked @endif>
                                                <label class="custom-control-label" for="smscustomSwitch1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="emailcustomSwitch1" @if($event->is_checked_email(auth()->user()->id))  checked @endif>
                                                <label class="custom-control-label" for="emailcustomSwitch1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="webhookcustomSwitch1" @if($event->is_checked_webhook(auth()->user()->id))  checked @endif>
                                                <label class="custom-control-label" for="webhookcustomSwitch1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div> 
            </div> 
        </div>
        
    </div>
@endsection

@section('script')
@endsection