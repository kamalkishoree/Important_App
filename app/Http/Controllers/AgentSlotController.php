<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use Exception;
use DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Traits\ApiResponser;

use Doctrine\DBAL\Driver\DrizzlePDOMySql\Driver;
use App\Model\{Agent,AgentSlot,AgentSlotRoster,SlotDay};


class AgentSlotController extends Controller
{
    use ApiResponser;
    public $blockColor = '#d71717';
    public $workingColor = '#43bee1';
    public $Blockedslots = '#8d6464';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $agent = Agent::where('id', $request->agent_id)->firstOrFail();
            if(!$agent){
                $this->error('Agent not fount!',405);
            }
          
            $dateNow = Carbon::now()->format('Y-m-d');
            $slotData = array();

            $block_time = explode('-', $request->blocktime);
            $start_date = date("Y-m-d H:i:s",strtotime($block_time[0]));
            $end_date   = date("Y-m-d H:i:s",strtotime($block_time[1]));
        
            $period   = CarbonPeriod::create($start_date, $end_date);
            $weekdays = $request->recurring == 'true' ? $request->week_day  : [1,2,3,4,5,6,7]; 
          
            $slot = new AgentSlot();
            $slot->agent_id     = $agent->id;
            $slot->start_time   = $request->start_time;
            $slot->end_time     = $request->end_time;
            $slot->start_date   = $start_date;
            $slot->end_date     = $end_date;
            $slot->recurring    =$request->recurring == 'true' ? 1 : 0;
            $slot->save();

            if(isset($slot->id)){
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorSlot  $vendorSlot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      
        $dateNow = Carbon::now()->format('Y-m-d');
        $agent = Agent::where('id', $request->agent_id)->firstOrFail();
    
        if($request->edit_type == 'day') {
            $slotDay = SlotDay::where('id', $request->edit_type_id)->where('day', $request->edit_day)->first();
            if(!$slotDay){
                $slotDay = new SlotDay();
                $slot = new AgentSlot();
            }
            else{
                $slot_id = $slotDay->slot_id;
                $slot = AgentSlot::where('id', $slot_id)->first();
                
                if($request->slot_type_edit == 'date'){
                    // delete slot day
                    $slotDay->delete();
                    // delete vendor slot
                    // $slot->delete();
                   
                    $dateSlot = new AgentSlotDate();
                    $dateSlot->agent_id         = $agent->id;
                    $dateSlot->start_time       = $request->start_time;
                    $dateSlot->end_time         = $request->end_time;
                    $dateSlot->specific_date    = $request->slot_date ?? $dateNow;
                    $dateSlot->working_today    = 1;
                    $dateSlot->save();
                    return $this->success('', __('Slot saved successfully!'));
                    
                }
            }

            $slot->agent_id     = $agent->id;
            $slot->start_time   = $request->start_time;
            $slot->end_time     = $request->end_time;
            $slot->save();
            $slotDay->slot_id =  $slot->id;
            $slotDay->day = $request->edit_day;
            $slotDay->save();

        }else{
            $dateSlot = AgentSlotDate::where('id', $request->edit_type_id)->first();

            if(!$dateSlot){
                $dateSlot = new AgentSlotDate();
            }
            else{
                if( $request->slot_type_edit == 'day' ){
                    $agent_id = $agent->id;
                    // delete date slot
                    $dateSlot->delete();
                    // delete day slot
                    $agent_slot_day = SlotDay::whereHas('agent_slot', function($q) use($agent_id){
                        $q->where('vendor_slots.agent_id', $agent_id);
                    })->where('day', $request->edit_day)->delete();

                    $slot = new AgentSlot();
                    $slot->agent_id     = $agent->id;
                    $slot->start_time   = $request->start_time;
                    $slot->end_time     = $request->end_time;
                    $slot->save();

                  

                    $sday = new SlotDay();
                    $sday->slot_id =  $slot->id;
                    $sday->day = $request->edit_day;
                    $sday->save();
                    return $this->success('', __('Slot saved successfully!'));
                }
            }
            $dateSlot->agent_id         = $agent->id;
            $dateSlot->start_time       = $request->start_time;
            $dateSlot->end_time         = $request->end_time;
            $dateSlot->specific_date    = $request->slot_date;
            $dateSlot->working_today    = 1;
            $dateSlot->save();

        }
        return $this->success('', __('Slot saved successfully!'));

    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorSlot  $vendorSlot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            $dateNow = Carbon::now()->format('Y-m-d');
            if($request->slot_type == 'date'){
                if($request->old_slot_type == 'day')
                {
                    AgentSlotDate::updateOrCreate([
                        'agent_id'=>$request->agent_id,
                        'specific_date' => $request->slot_date ?? $dateNow  
                    ],[
                        'working_today' => 0
                    ]);
                }else{
                    $dateSlot = AgentSlotDate::find($request->slot_id);
                    $dateSlot->delete();
                }
            } else {
                $slotDay = SlotDay::where('slot_id', $request->slot_id)->get();
                if($slotDay->count() == 1){
                    $vendorSlot = AgentSlot::find($request->slot_id);
                    $vendorSlot->delete();
                }
                $slot_day = SlotDay::where('id', $request->slot_day_id)->delete();
            }
            DB::commit(); //Commit transaction after all the operations
            return $this->success('', __('Slot deleted successfully!'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(array('success' => false, 'message'=>'Something went wrong.'));
        }
    }

    public function returnJson(Request $request, $domain = '', $id)
    {
        
        $Agent = Agent::findOrFail($id);
        $date = $day = array();

        if($request->has('start')){
            $start = explode('T', $request->start);
            $end  = explode('T', $request->end);

            $startDate = date('Y-m-d', strtotime($start[0])); 
            $endDate = date('Y-m-d', strtotime($end[0]));

            $datetime1 = new \DateTime($startDate);
            $datetime2 = new \DateTime($endDate);

            $interval = $datetime2->diff($datetime1);
            $days = $interval->format('%a');

            $date[] = $startDate;
            $day[] = 1;

            for ($i = 1; $i < $days; $i++) {
                $date[] = date('Y-m-d', strtotime('+'.$i.' day', strtotime($startDate)));
                $day[] = $i + 1;
            }
        }else{
            $startDate = '';
            $endDate ='';
        }

        $lst = count($date) - 1;
        // $slot = AgentSlot::select('agent_slots.*', 'slot_days.id as slot_day_id', 'slot_days.slot_id', 'slot_days.day')->join('slot_days', 'slot_days.slot_id', 'agent_slots.id')->where('agent_id', $id)->orderBy('slot_days.day', 'asc')->get();
     
        $AgentRoster = AgentSlotRoster::where('agent_id',$Agent->id)->whereBetween('schedule_date', [$startDate, $endDate])->orderBy('schedule_date','asc')->get();

        $showData = array();
        $count = 0;


            if($AgentRoster){
                foreach ($AgentRoster as $k => $v) {
                    $a_date = date('Y-m-d', strtotime($v->schedule_date));
                    $title = $v->memo ? $v->memo :'';
                    $color = $this->workingColor;
                    if($v->booking_type == 'blocked'){
                        $color = $this->Blockedslots;
                    }else if($v->booking_type == 'new_booking'){
                        $color = $this->workingColor;
                    }
                    
                    $showData[$count]['title'] = trim($title);
                    $showData[$count]['start'] = $a_date.'T'.$v->start_time;
                    $showData[$count]['end'] = $a_date.'T'.$v->end_time;
                    $showData[$count]['color'] = $color;
                    $showData[$count]['type'] = 'date';
                    $showData[$count]['roster_id'] = $v->id;
                    $showData[$count]['slot_id'] = $v->slot_id;
                    $count++;
                    
                }
            }

        
        echo $json  = json_encode($showData);
    }

    public function returnJsonOld(Request $request, $domain = '', $id)
    {
        
        $Agent = Agent::findOrFail($id);
        $date = $day = array();

        if($request->has('start')){
            $start = explode('T', $request->start);
            $end = explode('T', $request->end);

            $startDate = date('Y-m-d', strtotime($start[0])); 
            $endDate = date('Y-m-d', strtotime($end[0]));

            $datetime1 = new \DateTime($startDate);
            $datetime2 = new \DateTime($endDate);

            $interval = $datetime2->diff($datetime1);
            $days = $interval->format('%a');

            $date[] = $startDate;
            $day[] = 1;

            for ($i = 1; $i < $days; $i++) {
                $date[] = date('Y-m-d', strtotime('+'.$i.' day', strtotime($startDate)));
                $day[] = $i + 1;
            }
        }else{
            $dayArray = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
            foreach ($dayArray as $key => $value) {
                $th = ($value == 'sunday') ? 'previous sunday' : $value.' this week';
                $date[] =  date( 'Y-m-d', strtotime($th));
                $day[] = $key + 1;
            }
        }

        $lst = count($date) - 1;
        $slot = AgentSlot::select('agent_slots.*', 'slot_days.id as slot_day_id', 'slot_days.slot_id', 'slot_days.day')->join('slot_days', 'slot_days.slot_id', 'agent_slots.id')->where('agent_id', $id)->orderBy('slot_days.day', 'asc')->get();
        
        $slotDate = AgentSlotDate::whereBetween('specific_date', [$date[0], $date[$lst]])->orderBy('specific_date','asc')->get();

        $showData = array();
        $count = 0;

        foreach ($day as $key => $value) {
            $exist = 0;
            $start = $end = $color = '';

            if($slotDate){
                foreach ($slotDate as $k => $v) {
                    $title = '';
                    if($date[$key] == $v->specific_date){
                        $exist = 1;
                       

                        $showData[$count]['title'] = trim($title);
                        $showData[$count]['start'] = $date[$key].'T'.$v->start_time;
                        $showData[$count]['end'] = $date[$key].'T'.$v->end_time;
                        $showData[$count]['color'] = ($v->working_today == 0) ? '#43bee1' : '';
                        $showData[$count]['type'] = 'date';
                        $showData[$count]['type_id'] = $v->id;
                        $showData[$count]['slot_id'] = $v->id;
                        $count++;
                    }
                }
            }

            if($exist == 0){
                foreach ($slot as $k => $v) {
                    $title = '';
                    if($value == $v->day){

                        $showData[$count]['title'] = trim($title);
                        $showData[$count]['start'] = $date[$key].'T'.$v->start_time;
                        $showData[$count]['end'] = $date[$key].'T'.$v->end_time;
                        $showData[$count]['type'] = 'day';
                        $showData[$count]['color'] = ($v->working_today == 0) ? '#43bee1' : '';
                        $showData[$count]['type_id'] = $v->slot_day_id;
                        $showData[$count]['slot_id'] = $v->slot_id;
                        $count++;
                    }
                }
            } 
        }
        echo $json  = json_encode($showData);
    }
   

}
