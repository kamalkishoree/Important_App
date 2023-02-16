<?php
namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Model\ {
    Agent,
    AgentAttendence
};

class AgentAttendenceController extends BaseController
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getTodayAttendance(Request $request)
    {
        $user = Auth::user();
        try {
            $attendanceData = AgentAttendence::where('agent_id', $user->id)->where('start_date', $request->date)->first();

            if (! empty($attendanceData)) {
                $attendanceData['in_status'] = 1;
                if (empty($attendanceData->end_date)) {}
                return response()->json([
                    'data' => $attendanceData
                ]);
            } else {
                $attendanceData['agent_id'] = $user->id;
                $attendanceData['start_date'] = '';
                $attendanceData['start_time'] = '';
                $attendanceData['end_date'] = '';
                $attendanceData['end_time'] = '';
                $attendanceData['in_status'] = 0;
                $attendanceData['total'] = "00:00";
                return response()->json([
                    'data' => $attendanceData
                ]);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            \Log::info('Add Agent Attendence API!');
            \Log::info($request->all());

            $rules = [
                'start_time' => [
                    'required'
                ],
                'agent_id' => [
                    'required'
                ],
                'start_date' => [
                    'required'
                ]
            ];
            $customMessages = [];

            $validator = Validator::make($request->all(), $rules, $customMessages);

            $data = [];
            if ($validator->fails()) {
                return $this->error($validator->errors()
                    ->first(), 422);
            }

            if ($request->start_date != date('Y-m-d')) {
                return $this->error(__('Cannot in for past and coming date'), 400);
            }
            $agent = AgentAttendence::where([
                'agent_id' => $request->agent_id,
                'start_date' => $request->start_date
            ])->first();
            if ($agent) {
                return $this->error(__('Agent is already in for the day'), 400);
            }

            $data = [
                'start_time' => $request->start_time,
                'agent_id' => $request->agent_id,
                'start_date' => $request->start_date
            ];

            $agent = AgentAttendence::create($data);
            return response()->json([
                'data' => $agent,
                'status' => 200,
                'message' => "Agent in successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgentAttendence $id)
    {
        try {
            \Log::info('Add Agent Attendence API!');
            \Log::info($request->all());

            $rules = [
                'end_time' => [
                    'required'
                ],
                'end_date' => [
                    'required'
                ]
            ];
            $customMessages = [];

            $validator = Validator::make($request->all(), $rules, $customMessages);

            $data = [];
            if ($validator->fails()) {
                return $this->error($validator->errors()
                    ->first(), 422);
            }

            $agentAttendence = AgentAttendence::where([
                'id' => $request->id
            ])->first();
            if (empty($agentAttendence)) {
                return $this->error(__('Attendence data not found'), 400);
            }
            $data = [
                'end_time' => $request->end_time,
                'end_date' => $request->end_date
            ];

            $agentAttendence->update($data);
            return response()->json([
                'data' => $agentAttendence,
                'status' => 200,
                'message' => "Agent out successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
