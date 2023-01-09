<?php
namespace App\Traits;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Model\{Agent, AgentSlot,AgentSlotRoster};

trait AgentSlotTrait{
    use ApiResponser;
    //------------------------------Function created by harbans singh--------------------------//
    public static function SlotBooking($data)
    {
        try {
            $end_date =  $start_date = date("Y-m-d H:i:s",strtotime( $data['schedule_time']));
            $start_time = date("H:i:s",strtotime( $data['schedule_time']));
            $end_time   = date("H:i:s", strtotime($data['schedule_time']."+".$data['service_time']." minutes"));
        
            $AgentSlot = new AgentSlot();
        
            $AgentSlot->agent_id     = $data['agent'];
            $AgentSlot->start_time   = $start_time;
            $AgentSlot->end_time     = $end_time;
            $AgentSlot->start_date   = $start_date;
            $AgentSlot->end_date     = $end_date;
            $AgentSlot->recurring    = 0;
            $AgentSlot->save();
            \Log::info('AgentSlot create');
            \Log::info($data['agent']);
            if($AgentSlot){
                $slot_roster                 =   new AgentSlotRoster();
                $slot_roster->slot_id        =  $AgentSlot->id;
                $slot_roster->agent_id       =  $data['agent'];
                $slot_roster->start_time     =  $start_time;
                $slot_roster->end_time       =  $end_time;
                $slot_roster->schedule_date  =  $start_date;
                $slot_roster->booking_type   =  $data['booking_type'] ?? 'new_booking' ;
                $slot_roster->memo           =  $data['memo'] ??'';
                $slot_roster->order_id       =  $data['order_id'] ??'';
                $slot_roster->order_number   =  $data['order_number'] ??'';
                $slot_roster->save();
            }
            return 1;
        }catch (\Exception $e) {
            \Log::info($e->getMessage());
            return 2;
        }
    }
    //-------------------------------------------------------------------------------------------//
    
    public function saveAgentSlots($request){
        try {
            DB::beginTransaction();
            $agent = Agent::where('id', $request->agent_id)->firstOrFail();
            if(!$agent){
                $this->error('Agent not fount!',405);
            }
            
            $dateNow = Carbon::now()->format('Y-m-d');
            $slotData = array();

            
            $start_date = date("Y-m-d H:i:s",strtotime($request->start_date));
            $end_date   = date("Y-m-d H:i:s",strtotime($request->end_date));
        
            $period   = CarbonPeriod::create($start_date, $end_date);
            $weekdays = $request->recurring == 1 ? $request->week_day  : [1,2,3,4,5,6,7]; 
            
            $slot = new AgentSlot();
            $slot->agent_id     = $agent->id;
            $slot->start_time   = $request->start_time;
            $slot->end_time     = $request->end_time;
            $slot->start_date   = $start_date;
            $slot->end_date     = $end_date;
            $slot->recurring    = $request->recurring;
            $slot->save();
            
            if(isset($slot->id)){
                foreach ($weekdays as $k => $day) {
                    $slotData['slot_id']    = $slot->id;
                    $slotData['day']        = $day;
                    SlotDay::insert($slotData); 
                }
                $AgentSlotData = [];
                // Iterate over the period
                foreach ($period as $key => $date) {
                    $dayNumber = $date->dayOfWeek+1; // get day number 
                    if(in_array($dayNumber, $weekdays)){
                        $AgentSlotData[$key]['slot_id']        = $slot->id;
                        $AgentSlotData[$key]['agent_id']       = $request->agent_id;
                        $AgentSlotData[$key]['start_time']     = $request->start_time;
                        $AgentSlotData[$key]['end_time']       = $request->end_time;
                        $AgentSlotData[$key]['schedule_date']  = $date->format('Y-m-d H:i:s');
                        $AgentSlotData[$key]['booking_type']   = $request->booking_type ?? 'working_hours' ;
                        $AgentSlotData[$key]['memo']           = $request->memo ?? __('Working Hours');
                    }
                }
            }
            AgentSlotRoster::insert($AgentSlotData);
            DB::commit(); //Commit transaction after all the operations
            
            return $this->success('', __('Slot saved successfully!'));
        } catch (Exception $e) {
            
            DB::rollBack();
            return response()->json(array('success' => false, 'message'=>'Something went wrong.'));
        }
    
        
    
    }

}
