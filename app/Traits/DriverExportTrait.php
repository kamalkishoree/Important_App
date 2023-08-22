<?php

namespace App\Traits;

use App\Model\Order;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait DriverExportTrait
{
public function exportDriver(Request $request){
        $fileName = 'driver_list.csv';
        $data = $request->all();
        $user = Auth::user();
        $userid = $user->id;
        $type = $request->status;
        $orders = Order::with(['agent', 'getAgentPayout']);
        if ( $user->is_superadmin == 0 &&  $user->all_team_access == 0) {
            $orders = $orders->whereHas('agent.team.permissionToManager', function($q) use ($userid){
            $q->where('sub_admin_id', $userid);
        });}
        
        if($type == 'statement') {

            $orders = $orders->whereHas('getAgentPayout' , function($query) use($type) {
                $query->where('status', 1);
            });
        }
        $orders = $orders->where('status', 'completed');
        if (isset($request->date_filter)) {
            $date_filter = $request->date_filter;
            if($date_filter){
                
                $date_explode = explode('to', $date_filter);
                $from_date =  trim($date_explode[0]);
                $end_date = trim($date_explode[1]);
                
                $orders->whereBetween('order_time', [$from_date, $end_date]);
            }
        }
        if(isset($request->driver_id) ) {
            $orders->where('driver_id', $request->driver_id);
        }

        // Create Datatable
        $orders = $orders->orderBy('id', 'desc')->get();
        $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );
        
                   $columns = $this->headings();
        
                $callback = function() use($orders, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);
        
                    foreach ($orders as $order) {
        
                        fputcsv($file, $this->map($order));
                    }
        
                    fclose($file);
                };
        
                return response()->stream($callback, 200, $headers);
}
    
public function headings(): array{
        return [
            'Order No',
            'Delivery Boy Name',
            'Delivery Boy Phone',
            'Customer Name',
            'Cash to be collected',
            'Driver Cost',
            'Employee Commission Percentage',
            'Employee Commission Fixed',
            'Order Amount',
            'Date of order'
        ];
}
    
public function map($orders): array
{
        return [
            $orders->order_number ?? '',
            optional($orders->agent)->name ?? '',
            optional($orders->agent)->phone_number ?? '',
            optional($orders->customer)->name  ?? '',
            $orders->cash_to_be_collected  ?? '',
            $orders->driver_cost ?? '',
            $orders->agent_commission_percentage ?? '',
            $orders->agent_commission_fixed ?? '',
            $orders->order_cost ?? '',
            (optional($orders->created_at)->format("d-m-Y h:i A") ?? '')
        ];
}
}
