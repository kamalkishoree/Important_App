
<!DOCTYPE html>
<html>
<head>
  <title>invoice</title>
  <style type="text/css">
    .container{
      width: 600px;
      margin:auto;
      padding:10px;
    }
  </style>
</head>
<body>
  <div class="container">   
    <div class="invoice-container" ref="document" id="html">
      <table style="width:100%; height:auto;  text-align:center; " BORDER=0 CELLSPACING=0>
      <thead style="background:#fafafa; padding:8px;">
       <tr style="font-size: 20px;">
         <td colspan="4" style="padding:20px 0px;text-align: left;">
             <span class="logo-sm">
                <img src="{{$logoimage}}"
                    alt="" height="70" style="padding-top: 4px;">
            </span>
        </td>
       </tr>
      </thead>
      <tbody style="background:#ffff;padding:20px;">
       <tr>
         <td colspan="2" style="padding:20px 0px 0px 20px;text-align:left;font-size: 16px; font-weight: bold;color:#000;">{{ __('#') }}{{ $order->order_number ?? '' }}</td>
         <td colspan="2 " style="padding:20px 0px 0px 20px;text-align:right;font-size: 14px; color:rgb(105, 103, 103);">{{ __('Date') }}: {{ $NowDate }}</td>
        </tr>
       <tr>
         <td colspan="4" style="text-align:left;padding:10px 10px 10px 20px;font-size:14px;">{{ __('Order Invoice') }}</td>
       </tr>
      </tbody>
      </table>
      
      <table style="width:100%; height:auto; background-color:#fff; margin-top:0px;  padding:20px; font-size:12px; border: 1px solid #ebebeb; border-top:0px;">
      <thead>
       <tr style=" color: #6c757d;font-weight: bold; padding: 5px;">
         <td colspan="2" style="text-align: left;">{{ __('Driver') }}</td>
         
         <td  colspan="2" style="text-align: right;padding: 10px;">{{ __('Total Price') }}</td>
       </tr>
      </thead>
      <tbody>
       <tr>
         <td colspan="2"> {{ @$order->agent->name  }}</td>
         <td colspan="2" style="width:20%;font-size:13px; text-align: right; padding: 10px;">${{ $order->driver_cost  }}</td>
        
       </tr>
      </tbody>
      </table>
     
     
            

      <table style="width:100%; height:auto; background-color:#fff;padding:20px; font-size:12px; border: 1px solid #ebebeb; border-top:0">
      <tbody>
       <tr style="padding:20px;color:#000;font-size:15px">
         <td style="font-weight: bold;padding:5px 0px">{{ __('Total') }}</td>
         <td style="text-align:right;padding:5px 0px;font-weight: bold;font-size:16px;"></td>
       </tr>

       <tr>
         <td colspan="2" style="font-weight:bold;"><span style="color:#c61932;font-weight: bold;">{{ __('Payment Infomation:') }}</span> {{ $order->status }} </td>
       </tr>
      </tbody>
      <tfoot style="padding-top:20px;font-weight: bold;">
       <tr>
         <td style="padding-top:20px;">{{ __('Need help? Contact us') }} <span style="color:#c61932"> {{ $client->email }} </span></td>
       </tr>
      </tfoot>
      </table>
    </div>
  </div>
</body>
</html>
