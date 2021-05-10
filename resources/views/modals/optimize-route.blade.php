
<div id="optimize-route-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Optimize Route</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key={{$map_key}}"></script>
            <form id="optimizerouteform" action="" method="POST">
                @csrf
                <div class="modal-body py-0">
                    
                    <div class="row">                       

                        <div class="col-md-12">
                            <div class="card-box mb-0 p-0">
                                
                                <div class="row">

                                    <div class="col-lg-6 col-sm-6 mb-lg-0 mb-3">
                                        <div class="form-group" id="DriverStartTime">
                                            {!! Form::label('title', 'Driver starts Day at',['class' => 'control-label']) !!}
                                            {!! Form::time('driver_start_time', '09:00', ['class' => 'form-control driverStartTime']) !!}
                                            
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Please enter the driver start time</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 mb-lg-0 mb-3">
                                        <div class="form-group" id="DriverTaskDuration">
                                            {!! Form::label('title', 'Task Duration(in min)',['class' => 'control-label']) !!}
                                            {!! Form::text('task_duration', 10, ['class' => 'form-control driverTaskDuration onlynumber']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Please enter the task duration</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 mb-lg-0 mb-3">
                                        <div class="form-group" id="DriverBrakeStartTime">
                                            {!! Form::label('title', 'Driver Break Start time',['class' => 'control-label']) !!}
                                            {!! Form::time('brake_start_time', '13:00', ['class' => 'form-control driverBrakeStartTime']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Please enter the driver break start time</strong>
                                            </span>
                                        </div> 
                                    </div>
                                    <div class="col-lg-6 col-sm-6 mb-lg-0 mb-3">
                                        <div class="form-group" id="DriverBrakeEndTime">
                                            {!! Form::label('title', 'Driver Break End time',['class' => 'control-label']) !!}
                                            {!! Form::time('brake_end_time', '14:00', ['class' => 'form-control driverBrakeEndTime']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Please enter the driver break end time</strong>
                                            </span>
                                        </div> 
                                    </div>

                                    <div class="col-12 mb-lg-0 mb-3">
                                        <div class="form-group" id="">
                                            {!! Form::label('title', 'Driver Start Location',['class' => 'control-label d-block']) !!}
                                            
                                            <input class="align-middle" type="radio" name="driver_start_location" value="current" checked> <span class="align-middle">Current location</span>
                                            <input class="align-middle ml-2" type="radio" name="driver_start_location" value="select"> <span class="align-middle">Select Location</span> 
                                            <input class="align-middle ml-2" type="radio" name="driver_start_location" value="task_location"> <span class="align-middle">Select Task</span> 
                                            
                                        </div> 
                                    </div>

                                    <div class="col-lg-12 col-sm-6 mb-lg-0 mb-3" id="addressBlock" style="display: none;">
                                        <div class="form-group input-group mb-1" >
                                            

                                            <input type="text" id="searchTextField" name="address" class="form-control address cust_add autocomplete" placeholder="Address">
                                            <div class="input-group-append">
                                                <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="add1"> <i class="mdi mdi-map-marker-radius"></i></button>
                                            </div>
                                            <input type="hidden" name="latitude" id="opt-route-latitude" value="0" class="" />
                                            <input type="hidden" name="longitude" id="opt-route--longitude" value="0" class="" />
                                            
                                        </div>

                                    </div>

                                    <div class="col-lg-12 col-sm-6 mb-lg-0 mb-3" id="addressTaskBlock" style="display: none;">
                                        <div class="form-group input-group mb-1" >
                                            <select class="form-control" name="task_start_location" id="selectedtasklocations">                                                
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                
                                <input type="hidden" name="route_taskids" id="routeTaskIds">
                                <input type="hidden" name="distance_matrix" id="routeMatrix">
                                <input type="hidden" name="route_optimize" id="routeOptimize">
                                <input type="hidden" name="route_agentid" id="routeAgentid">
                                <input type="hidden" name="route_date" id="routeDate">
                                <input type="hidden" name="optimize_type" id="optimizeType">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-blue waves-effect waves-light" onclick="cancleForm()">Cancel</button> --}}
                    <button type="button" class="btn btn-blue waves-effect waves-light submitoptimizeForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

function initialize() {
  var input = document.getElementById('searchTextField');
//   new google.maps.places.Autocomplete(input);
  var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                
                document.getElementById('opt-route-latitude').value = place.geometry.location.lat();
                document.getElementById('opt-route--longitude').value = place.geometry.location.lng();
            });
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
