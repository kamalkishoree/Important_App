<div class="row">
    <div class="col-md-12">
        <table class="table">
            <tr>
                <td>Agent Name</td>
                <td>
                    @php
                        $name = '';
                        if(!empty($data->agent)){
                            $name = $data->agent->name;
                        }
                    @endphp
                    {{ $name }}
                </td>
            </tr>
            <tr>
                <td>Amount:</td>
                <td>{{ $data->amount}}</td>
            </tr>
            @if($data->payment_type == 0)
                <tr>
                    <td>Receipt:</td>
                    <td><a href="{{ $data->file}}" target="_blank"><img width="200" height="200" src="{{ $data->file}}"/></a></td>
                </tr>
            @endif
            <tr>
                <td>Date:</td>
                <td> {{ $data->date }}</td>
            </tr>
            @if($data->status == 0)
                <tr>
                    <td>Action</td>
                    <td>
                        <select name="payment_action" id="payment_action" data-status ="{{ $data->status }}" class="form-control">
                            <option value="">Select Status</option>
                            <option value="1">Approval</option>
                            <option value="2">Rejected</option>
                        </select>
                    </td>
                </tr>
                <tr id="rejected"></tr>
                @else
                <tr>
                    <td>Status</td>
                    <td>
                        @if($data->status == 1)
                            Approval
                        @elseif($data->status == 2)
                            Rejected
                        @endif

                    </td>
                </tr>
            @endif

            @if($data->status == 2)
                <td>Reason</td>
                <td>{{ $data->reason }}</td>
            @endif
        </table>

        <div class="error" id="frm-error"></div>
    </div>
</div>


