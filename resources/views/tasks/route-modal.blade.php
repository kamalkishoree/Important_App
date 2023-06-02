    <style>


    ul.custom-list {
    list-style: none;
    margin: 0;
    padding: 0;
    position: relative;
    }

    ul.custom-list:before {
    content: "";
    display: inline-block;
    width: 2px;
    background: lightblue;
    position: absolute;
    left: 3px;
    top: 5px;
    height: calc(100% - 10px);
    }
    ul.custom-list li.is_delivered:before {
    background-color: blue; /* Change dot color to blue */
    }

    ul.custom-list li {
    position: relative;
    padding-left: 15px;
    margin-bottom: 15px;
    }

    ul.custom-list li:before {
    content: "";
    display: inline-block;
    width: 8px;
    height: 8px;
    background: red;
    position: absolute;
    left: 0;
    top: 5px;
    border-radius: 10px;
    }

    .agent-select {
    display: inline-block;
    margin-left: 10px;
  }
 
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                
                <ul class="custom-list">
                    @foreach($order->task as $route)
                    @php
                    if($route->task_type_id ==1)
                    {
                    $type ='Pickup';
                    }else{
                    $type ='Drop Off';
                    }

                    $class='';
                    if($route->task_status == 1 || $route->task_status == 2)
                    {
                      $class= 'is_delivered';
                    }
                    @endphp
                    <li onclick="getTaskDetail({{$route->id}})" class="{{ $class}}">
                    @if($route->task_status  == 0 )
                    {{ $route->location->address}}
                    @endif
                    @if($route->task_type_id  == 1)
                    
                        @if($route->task_status  == 1 )
                        <span class="agent-name"> Picked up by Agent {{$order->agent->name}}  from {{ $route->location->address}}</span>
                        


                        @else
                         <select class="agent-select select_agent" data-id="{{ $order->id}}" task-id="{{$route->id}}">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>

                        @endif
                       
                    @else
                    @if($route->task_type_id  == 2)
                    
                    @if($route->task_status  == 2 )

                    <span class="agent-name"> Dropped at   {{ $route->location->address}} by Agent {{$order->agent->name}}  from</span>
                    

                    @endif
                    @endif
                    @endif
                      
                    </li>
                   
                    @endforeach
                </ul>
            </div>
        </div>
    </div>