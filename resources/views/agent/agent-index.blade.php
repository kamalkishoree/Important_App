  @foreach ($agents as $agent)
  <tr>
      <td>{{ $agent->id }}</td>

      <td>
       @php
        $src = (isset($agent->profile_picture) ? $agent->imgproxyurl . Storage::disk('s3')->url($agent->profile_picture) : Phumbor::url(URL::to('/asset/images/no-image.png')));
          @endphp
          <img src="{{  $src }}">
      </td>
      <td>
        <?php if (empty($agent->deleted_at)) { ?>
        <div class="inner-div"> <a href={{route('agent.edit', $agent->id)}} class="action-icon editIcon" agentId="{{$agent->id}}"> <i class="mdi mdi-square-edit-outline"></i></a></div>
          <?php } ?>
        {{ $agent->name}}
    </td>
      <td>
        {{ $agent->phone_number}}
    </td>
      <td>
        @php

if($agent->type == 'undefined')
{
    $type = "Freelancer";
}
else{
    $type = $agent->type;
}
        @endphp
        {{ $type }}
    </td>
      <td>{{ (isset($agent->team->name) ? $agent->team->name : __('Team Not Alloted')) }}</td>
      <td>{{ (isset($agent->warehouse->name) ? $agent->warehouse->name : __('-')) }}</td>

      <td>
        @php
        $cash = $agent->order->where('status', 'completed')
                            ->sum('cash_to_be_collected');

        @endphp
        {{ number_format((float) $cash, 2, '.', '')  }}

      </td>
      <td>

        @php
        $orders = $agent->order->where('status', 'completed')
                    ->sum('driver_cost');

        @endphp
        {{
             number_format((float) $orders, 2, '.', '')
        }}
      </td>
      <td>
@php

$receive = $agent->agentPayment->sum('cr');

@endphp
{{ number_format((float) $receive, 2, '.', '')}}

      </td>
      <td>
@php

$pay = $agent->agentPayment->sum('dr');

@endphp
{{ number_format((float) $pay, 2, '.', '')}}

      </td>

      <td>
@php


$cash = $agent->order->where('status', 'completed')
                    ->sum('cash_to_be_collected');
                $orders = $agent->order->where('status', 'completed')
                    ->sum('driver_cost');
                $receive = $agent->agentPayment->sum('cr');
                $pay = $agent->agentPayment->sum('dr');

                $payToDriver = ($pay - $receive) - ($cash - $orders);


@endphp
    {{ number_format((float) $payToDriver, 2, '.', '')}}

      </td>
      <td>
        {{  $agent->subscriptionPlan ? $agent->subscriptionPlan->plan->title : ''}}
      </td>
      <td>
        {{

$agent->subscriptionPlan ? convertDateTimeInTimeZone($agent->subscriptionPlan->end_date, $timezone) : ''
        }}
      </td>
      <td>
        

      @if (! empty($agents->deleted_at))
        <span class="badge badge-pill badge-danger pill-state">Deleted</span>
      @else 
        @if($agent->is_approved == 1)
          <span class="badge badge-pill badge-success pill-state">Active</span>
        @endif
        @if($agent->is_approved == 2)
          <span class="badge badge-pill badge-secondary pill-state">Blocked</span>
        @endif
      @endif

      </td>
      <td>

        @php

if (! empty($agent->agentRating())) {
    echo number_format($agent->agentRating()
                        ->avg('rating'), 2, '.', '');
                } else {
                    echo '0.00';
                }
        @endphp
      </td>
      <td>
        {{ convertDateTimeInTimeZone($agent->created_at, $timezone)}}
      </td>
      <td>
        {{ convertDateTimeInTimeZone($agent->updated_at, $timezone) }}
      </td>
      <td>
        @php

        $approve_action = '';
                if ($agent->is_driver_slot == 1 || $agent->is_attendence == 1) {
                    $approve_action .= '<div class="inner-div agent_slot_button" data-agent_id="' . $agent->id . '" data-status="2" title="Working Hours"><i class="dripicons-calendar mr-1" style="color: green; cursor:pointer;"></i></div>';
                }
                if ($agent->status == 1) {
                    $approve_action .= '<div class="inner-div agent_approval_button" data-agent_id="' . $agent->id . '" data-status="2" title="Reject"><i class="fa fa-user-times" style="color: red; cursor:pointer;"></i></div>';
                } else if ($agent->status == 0) {
                    $approve_action .= '<div class="inner-div agent_approval_button" data-agent_id="' . $agent->id . '" data-status="1" title="Approve"><i class="fas fa-user-check" style="color: green; cursor:pointer;"></i></div><div class="inner-div ml-1 agent_approval_button" data-agent_id="' . $agent->id . '" data-status="2" title="Reject"><i class="fa fa-user-times" style="color: red; cursor:pointer;"></i></div>';
                } else if ($agent->status == 2) {
                    $approve_action .= '<div class="inner-div agent_approval_button" data-agent_id="' . $agent->id . '" data-status="1" title="Approve"><i class="fas fa-user-check" style="color: green; cursor:pointer;"></i></div>';
                }
                $action = '' . $approve_action . '
                               <!-- <div class="inner-div"> <a href="' . route('agent.edit', $agent->id) . '" class="action-icon editIcon" agentId="' . $agent->id . '"> <i class="mdi mdi-square-edit-outline"></i></a></div>-->
                                    <div class="inner-div">
                                        <form id="agentdelete' . $agent->id . '" method="POST" action="' . route('agent.destroy', $agent->id) . '">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                            <input type="hidden" name="_method" value="DELETE">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete" agentid="' . $agent->id . '"></i></button>
                                            </div>
                                        </form>
                                    </div>
                               ';
                               echo  $action   ;
        @endphp

      </td>

  </tr>
@endforeach
