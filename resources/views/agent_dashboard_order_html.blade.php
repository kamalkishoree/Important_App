<?php
    if(isset($distance_matrix[0]))
    {
        if($unassigned_orders[0]['task_order']==0){
            $opti0 = "yes";
        }else{
            $opti0 = "";
        }
        $routeperams0 = "'".$distance_matrix[0]['tasks']."','".json_encode($distance_matrix[0]['distance'])."','".$opti0."',0,'".$date."'";
        $optimize0 = '<span class="optimize_btn" onclick="RouteOptimization('.$routeperams0.')">'.__("Optimize").'</span>';
        $params0 = "'".$distance_matrix[0]['tasks']."','".json_encode($distance_matrix[0]['distance'])."','yes',0,'".$date."'";
        $turnbyturn0 = '<span class="navigation_btn optimize_btn" onclick="NavigatePath('.$routeperams0.')">'.__("Export").'</span>';
    }else{
        $optimize0="";
        $params0 = "";
        $turnbyturn0 = "";
    }
?>
@php
    $date = date('Y-m-d');
    use Carbon\Carbon;
@endphp
{{-- @if( !empty($unassigned_orders) )
    <div id="accordion" class="overflow-hidden">
        <div id="handle-dragula-left0" class="dragable_tasks" agentid="0"  params="{{ $params0 }}" date="{{ $date }}">
            @foreach($unassigned_orders as $orders)
                @foreach($orders['task'] as $tasks)
                    <div class="card-body" task_id ="{{ $tasks['id'] }}">
                        <div class="p-2 assigned-block">
                            @php
                                $st ="Unassigned";
                                $color_class = "assign_";
                                if($orders['status'] == "unassigned"){
                                $class = "unassigned-badge"; 
                                }else{
                                    $class = "assigned-badge";
                                }
                                if($tasks['task_type_id']==1)
                                {
                                    $tasktype = "Pickup";
                                    $pickup_class = "yellow_";
                                }elseif($tasks['task_type_id']==2)
                                {
                                    $tasktype = "Dropoff";
                                    $pickup_class = "green_";
                                }else{
                                    $tasktype = "Appointment";
                                    $pickup_class = "assign_";
                                    }
                            @endphp

                            <div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-9 d-flex">
                                        @php
                                        if($tasks['assigned_time']=="")
                                        {
                                            $tasks['assigned_time'] = date('Y-m-d H:i:s');
                                        }
                                            $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                                            $order = Carbon::createFromFormat('Y-m-d H:i:s', $tasks['assigned_time'], 'UTC');

                                            //$order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                            $order->setTimezone($client_timezone);
                                        @endphp

                                        <h5 class="d-inline-flex align-items-center justify-content-between"><i class="fas fa-bars"></i> <span>{{date(''.$timeformat.'', strtotime($order))}}</span></h5>
                                        <h6 class="d-inline"><img class="vt-top"
                                            src="{{ asset('demo/images/ic_location_blue_1.png') }}"> {{ isset($tasks['location']['address'])? $tasks['location']['address']:'' }} <span class="d-block">{{ isset($tasks['location']['short_name'])? $tasks['location']['short_name']:'' }}</span>
                                            <p>
                                                @if(!empty($orders['agent']))
                                                    <span class="badge badge-blue text-white">{{ucfirst($orders['agent']['name'])}}</span>
                                                @else
                                                    <span class="badge badge-danger text-white">{{__('Unassigned')}}</span>
                                                @endempty
                                            </p>
                                        </h6>
                                        
                                    </div>
                                    <div class="col-3">
                                        <button class="assigned-btn float-right mb-2 {{$pickup_class}}">{{__($tasktype)}}</button>
                                        <button class="assigned-btn float-right {{$color_class}} {{$class}}" data-id="{{$orders['id']}}">{{ucfirst($orders['status'])}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @endforeach
        </div>
    </div>
@else
    <div class="no-data"><h5 class="text-center">no route found.</h5></div>
@endif --}}

<div id="accordion" class="overflow-hidden @if($checkuserroutes == 'assigned') {{__('d-none')}} @endif @if($agent_ids != '') {{__('d-none')}} @endif">
    <div id="handle-dragula-left0" class="dragable_tasks customui_card" agentid="0"  params="{{ $params0 }}" date="{{ $date }}">
        @foreach($unassigned_orders as $orders)
            @foreach($orders['task'] as $tasks)
                <div class="card-body" task_id ="{{ $tasks['id'] }}">
                    <div class=" assigned-block">
                        @php
                            $st ="Unassigned";
                            $color_class = "assign_";
                            if($tasks['task_type_id']==1)
                            {
                                $tasktype = "Pickup";
                                $pickup_class = "yellow_";
                            }elseif($tasks['task_type_id']==2)
                            {
                                $tasktype = "Dropoff";
                                $pickup_class = "green_";
                            }else{
                                $tasktype = "Appointment";
                                $pickup_class = "assign_";
                                }
                        @endphp
                        <div>
                            <div class="pick_drop_item_list">
                                <div class="col-12 ">
                                    <i class="fas fa-bars"></i>
                                    @php
                                    if($tasks['assigned_time']=="")
                                    {
                                        $tasks['assigned_time'] = date('Y-m-d H:i:s');
                                    }
                                        $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                                        $order = Carbon::createFromFormat('Y-m-d H:i:s', $tasks['assigned_time'], 'UTC');

                                        //$order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                        $order->setTimezone($client_timezone);
                                    @endphp

                                    <h5 class="w-100 d-flex align-items-center  justify-content-between"> 
                                        <span>{{date(''.$timeformat.'', strtotime($order))}}</span>
                                        {{-- <p>
                                            @if(!empty($agent))
                                                <span class="badge ">{{ucfirst($agent['name'])}}</span>
                                                @else
                                                <span class="badge badge-danger text-white unassigned-badge" data-id="{{$orders['id']}}">{{__('Unassigned')}}</span>
                                            @endempty
                                        </p> --}}
                                        <button class="assigned-btn float-left ml-1 unassigned-badge {{$color_class}}" data-id="{{$orders['id']}}">{{__($st)}}</button>
                                        
                                    </h5>
                                    <div class="second_list_pick w-100 d-flex align-items-center justify-content-between">
                                         <h6 class="d-inline"><img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}"> {{ isset($tasks['location']['address'])? $tasks['location']['address']:'' }} <span class="d-block">{{ isset($tasks['location']['short_name'])? $tasks['location']['short_name']:'' }}</span></h6>
                                         <button class="assigned-btn float-left  mb-2 {{$pickup_class}}">{{__($tasktype)}}</button>
                                    </div>
                                        {{-- <div> --}}
                                           
                                            
                                        {{-- </div> --}}
                                
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>

<div id="accordion" class="overflow-hidden @if($checkuserroutes == 'unassigned') {{__('d-none')}} @endif">
    @foreach ($teams as $team)
        @foreach ($team['agents'] as $agent)
            @if(!empty($agent['order']))  
                <div id="handle-dragula-left0" class="dragable_tasks customui_card assigned_agent" agentid="0"  params="{{ $params0 }}" date="{{ $date }}">
                    @foreach ($agent['order'] as $orders)
                        @foreach ($orders['task'] as $tasks)
                            <div class="card-body" task_id ="{{ $tasks['id'] }}">
                                <div class=" assigned-block ">
                                    @php
                                        $st ="Unassigned";
                                        $color_class = "assign_";
                                        if($orders['status'] == "unassigned"){
                                            $class = "unassigned-badge"; 
                                        }else{
                                            $class = "assigned-badge";
                                        }
                                        if($tasks['task_type_id']==1)
                                        {
                                            $tasktype = "Pickup";
                                            $pickup_class = "yellow_";
                                        }elseif($tasks['task_type_id']==2)
                                        {
                                            $tasktype = "Dropoff";
                                            $pickup_class = "green_";
                                        }else{
                                            $tasktype = "Appointment";
                                            $pickup_class = "assign_";
                                        }
                                    @endphp
                                    <div>
                                        <div class="pick_drop_item_list">
                                            <div class="col-12">
                                                <i class="fas fa-bars"></i>
                                                @php
                                                    if($tasks['assigned_time']=="")
                                                    {
                                                        $tasks['assigned_time'] = date('Y-m-d H:i:s');
                                                    }
                                                    $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                                                    $order = Carbon::createFromFormat('Y-m-d H:i:s', $tasks['assigned_time'], 'UTC');
                                                    //$order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                                    $order->setTimezone($client_timezone);
                                                @endphp
                                                <h5 class="w-100 d-flex align-items-center justify-content-between">
                                                     <span>{{date(''.$timeformat.'', strtotime($order))}}</span>
                                                     <p>
                                                        @if(!empty($agent))
                                                            <span class="badge">{{ucfirst($agent['name'])}}</span>
                                                            @else
                                                            <span class="badge badge-danger text-white">{{__('Unassigned')}}</span>
                                                        @endempty
                                                    </p>
                                                    <button class="assigned-btn float-right {{$color_class}} {{$class}}" data-id="{{$orders['id']}}">{{ucfirst($orders['status'])}}</button>
                                                </h5>
                                                <div class="second_list_pick w-100 d-flex align-items-center justify-content-between">
                                                    <h6 class="d-inline"><img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}"> {{ isset($tasks['location']['address'])? $tasks['location']['address']:'' }} <span class="d-block">{{ isset($tasks['location']['short_name'])? $tasks['location']['short_name']:'' }}</span>
                                                        
                                                    </h6>
                                                    <button class="assigned-btn float-right mb-2 {{$pickup_class}}">{{__($tasktype)}}</button>
                                                </div>
                                               
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            @endif
        @endforeach    
    @endforeach
</div>