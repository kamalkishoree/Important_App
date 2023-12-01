    <!-- Bannar Section -->
    {{-- <section class="bannar header-setting"> --}}
        
    <div class="container-fluid p-0">
        <div class="row coolcheck no-gutters">
            {{-- <div class="pageloader" style="display: none;">
                <div class="box">
                    <h4 class="routetext"></h4>
                    <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
                </div>
            </div> --}}
            <div id="scrollbar" class="col-md-3 col-xl-3 left-sidebar pt-3">
                <div class="side_head mb-2 py-2">
                    <div class="d-flex align-items-center justify-content-center mb-2"> 
                        <i class="mdi mdi-sync mr-1" onclick="reloadData()" aria-hidden="true"></i>
                        <div class="radio radio-primary form-check-inline ml-3 mr-2">
                            <input type="radio" id="user_status_all" value="2" name="user_status" class="checkUserStatus" checked>
                            <label for="user_status_all"> {{__("All")}} </label>
                        </div>
                        <div class="radio radio-primary form-check-inline">
                            <input type="radio" id="user_status_online" value="1" name="user_status" class="checkUserStatus">
                            <label for="user_status_online"> {{__("Online")}} </label>
                        </div>
                        <div class="radio radio-info form-check-inline mr-2">
                            <input type="radio" id="user_status_offline" value="0" name="user_status" class="checkUserStatus">
                            <label for="user_status_offline"> {{__("Offline")}} </label>
                        </div>
                        
                        {{-- <span class="allAccordian ml-2"><span class="" onclick="openAllAccordian()">{{__("Open All")}}</span></span> --}}
                    </div>
                   <div class="row search_bar">
                        <div class="col-md-6">
                            <div class="form-group mb-0 ml-1">
                                <select name="team_id[]" id="team_id" multiple="multiple" class="form-control">
                                    @foreach ($searchTeams as $team)
                                        <option value="{{$team->id}}">{{$team->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0 ml-1">
                                <input type="text" class="form-control" name="search_by_name" id="search_by_name" value="" placeholder="Search By Name" />
                            </div>
                        </div>
                   </div>
                </div>
                <div  id="teams_container">
                    @include('dashboard.parts.layout-'.$dashboard_theme.'.ajax.agent')
                </div>
                <form id="pdfgenerate" method="post" enctype="multipart/form-data" action="{{ route('download.pdf') }}">
                    @csrf
                    <input id="pdfvalue" type="hidden" name="pdfdata">
                </form>
            </div>
            <div class="col-md-6 col-xl-6">
                <div class="map-wrapper">
                    <div style="width: 100%">
                        <div id="map_canvas" style="width: 100%; height:calc(100vh - 70px);"></div>
                    </div>
                    {{-- <div class="contant">
                        <div class="bottom-content">
                            <input type="text"  id="basic-datepicker" class="datetime" value="{{date('Y-m-d', strtotime($date))}}" data-date-format="Y-m-d">
                            <div style="display:none">
                                <input class="newchecks filtercheck teamchecks" cla type="checkbox" value="-1" name="teamchecks[]" checked>
                                <input class="taskchecks filtercheck" type="checkbox" name="taskstatus[]" value="5" checked>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            {{-- @dd($unassigned_orders) --}}
            <div id="scrollbar" class="col-md-3 col-xl-3 left-sidebar pt-3">
                <div class="side_head mb-2 py-2">
                    <div class="select_bar_date mb-2 d-flex align-items-center justify-content-center">
                        <input type="date"  id="basic-datepicker" class="datetime form-control" value="{{date('Y-m-d', strtotime($date))}}" data-date-format="YY-mm-dd" onchange="handler(this);" style="width: 250px;">
                        <div style="display:none">
                            <input class="newchecks filtercheck teamchecks" cla type="checkbox" value="-1" name="teamchecks[]" checked>
                            <input class="taskchecks filtercheck" type="checkbox" name="taskstatus[]" value="5" checked>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mb-2"> 
                        <div class="radio radio-primary form-check-inline ml-3 mr-2">
                            <input type="radio" id="user_all_routes" value="" name="user_routes" class="checkUserRoutes" checked>
                            <label for="user_all_routes"> {{__("All")}} </label>
                        </div>
                        <div class="radio radio-primary form-check-inline">
                            <input type="radio" id="user_unassigned_routes" value="unassigned" name="user_routes" class="checkUserRoutes">
                            <label for="user_unassigned_routes"> {{__("Unassigned")}} </label>
                        </div>
                        <div class="radio radio-info form-check-inline mr-2">
                            <input type="radio" id="user_assigned_routes" value="assigned" name="user_routes" class="checkUserRoutes">
                            <label for="user_assigned_routes"> {{__("Assigned")}} </label>
                        </div>                        
                        {{-- <span class="allAccordian ml-2"><span class="" onclick="openAllAccordian()">{{__("Open All")}}</span></span> --}}
                    </div>
                    <div class="select_bar">
                        <div class="form-group mb-0 ml-1">
                        <select name="agent_id[]" id="agent_id" multiple="multiple" class="form-control">
                              
                              @foreach ($agentsData as $agent)
                                  @php
                                  
                                      $checkAgentActive = ($agent['is_available'] == 1) ? ' ('.__('Online').')' : ' ('.__('Offline').')';
                                  @endphp
                                      <option value="{{$agent['id']}}">{{ ucfirst($agent['name']). $checkAgentActive }}</option>
                                  @endforeach
                              </select>
                        </div>
                    </div>
                </div>


                {{-- agent section  --}}
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
            <div id="agent_route_container">
                <div id="accordion" class="overflow-hidden">
                    <!-- dragable_tasks -->
                    <div id="handle-dragula-left0" class=" " agentid="0"  params="{{ $params0 }}" date="{{ $date }}">
                        @include('dashboard.parts.layout-'.$dashboard_theme.'.ajax.order')
                    </div>
                </div>
            </div>
                {{-- agent section end --}}




               
                <form id="pdfgenerate" method="post" enctype="multipart/form-data" action="{{ route('download.pdf') }}">
                    @csrf
                    <input id="pdfvalue" type="hidden" name="pdfdata">
                </form>
            </div>
        </div>
    </div>
    <?php   // for setting default location on map
        $agentslocations = array();
        if(!empty($agents)){
            foreach ($agents as $singleagent) {
                if((!empty($singleagent['agentlog'])) && ($singleagent['agentlog']['lat']!=0) && ($singleagent['agentlog']['long']!=0))
                {
                    $agentslocations[] = $singleagent['agentlog'];
                }
            }
        }
        $defaultmaplocation['lat'] = $defaultCountryLatitude;
        $defaultmaplocation['long'] = $defaultCountryLongitude;
        $agentslocations[] = $defaultmaplocation;
    ?>





























<script>
    var channelname = "orderdata{{ $client_code }}{{ date('Y-m-d', time()) }}";
    var logchannelname = "agentlog{{ $client_code }}{{ date('Y-m-d', time()) }}";
    var imgproxyurl = {!! json_encode($imgproxyurl) !!};
    var optimizeRouteUrl = "{{ url('/optimize-route') }}";
    var optimizeArrangeRouteUrl = "{{ url('/optimize-arrange-route') }}";
    var assignAgentUrl = "{{ route('assign.agent') }}";
    var getRouteDetailUrl = "{{ route('get-route-detail') }}";
    var X_CSRF_TOKEN = '{{ csrf_token() }}';
    var iconsRoute = "{{ asset('assets/newicons/') }}";
    var teamDataUrl = "{{ route('dashboard.teamsdata') }}";
    var orderDataUrl = "{{ route('dashboard.orderdata') }}";
    var channelName = "orderdata{{ $client_code }}";
    var logChannelName = "agentlog{{ $client_code }}";
    var dashboardTheme = "{{ $dashboard_theme }}";
    var exportPathUrl = "{{ url('/export-path') }}";
    var getTasks = "{{ url('/get-tasks') }}";
    var arrangeRoute  = "{{ url('/arrange-route') }}";
    var getAgentNomenclature = "{{ __(getAgentNomenclature()) }}";


   
    

    // $(".datetime").on('change', function(){
    //     loadTeams(1, 1);
    //     loadOrders(1, 1);
    //     old_channelname = channelname;
    //     old_logchannelname = logchannelname;
    //     channelname = "orderdata{{ $client_code }}"+$(this).val();
    //     logchannelname = "agentlog{{ $client_code }}"+$(this).val();
    //     if(old_channelname != channelname)
    //     {
    //         ListenDataChannel();
    //         ListenAgentLogChannel();
    //     }
    // })

    //function fot optimizing route
    


    



    

</script>