@extends('layouts.vertical', ['title' => 'Geo Fence'])

@section('css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card-box">
                <div class="dropdown float-right">
                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        
                        <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                        
                        <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                        
                        <a href="javascript:void(0);" class="dropdown-item">Action</a>
                    </div>
                </div>

                <h4 class="header-title mb-3">Agents</h4>

                <div class="table-responsive">
                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                        <thead class="thead-light">
                            <tr>
                                <th>Agent Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Team Name</th>
                                <th>Manager</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agents as $agent)
                            <tr>
                                <td>
                                    <h5 class="m-0 font-weight-normal">{{ $agent->name }}</h5>
                                </td>

                                <td>
                                    {{ $agent->email }}
                                </td>

                                <td>
                                    {{ $agent->phone_number }}
                                </td>

                                <td>
                                    {{ $agent->team->manager->name }}
                                </td>

                                <td>
                                    asdf
                                </td>

                                <td>
                                    <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div> <!-- end .table-responsive-->
            </div> <!-- end card-box-->
        </div> 
    </div>
</div>
@endsection

@section('script')

@endsection