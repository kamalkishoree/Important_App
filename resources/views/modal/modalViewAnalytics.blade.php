
<div class="table-responsive">
  <table class="table table-borderless table-hover table-nowrap table-centered m-0">
  <thead class="thead-light">
      <tr>
         <th>Order Number</th>
         <th>Customer ID</th>
         <th>Customer</th>
         <th>Agent</th>
         <th>Pricing</th>
         <th>Status</th>
      </tr>
  </thead>
    <tbody id="agent_view_analytics_records">
     
      @if($orders)
          @foreach ($orders as $order)
            <tr>
              <td><a href="{{ url('tasks/'.$order->id.'/edit') }}" target="_blank" title="Edit Route">{{ $order->order_number }}</a></td>  
              <td>{{ $order->customer->id ?? '' }}</td>  
              <td>{{ $order->customer->name ?? ''}}</td>  
              <td>{{ $order->agent->name ?? ''  }}</td>  
              <td>{{ $order->order_cost }}</td>  
              <td>{{ ucfirst($data_status) }}</td>  
            </tr> 
          @endforeach
      @endif
    </tbody>
  </table>
</div>