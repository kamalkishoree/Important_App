<?php

namespace App\Http\Controllers\Rating;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log,DB;
use DataTables;
use App\Model\{RatingTypes};
use App\Traits\ResponseTrait;

class RatingTypeController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        
        	$data = RatingTypes::get();// query();//
          
           // pr($data->toArray());
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('action', function ($data) use ($request) {
                        $approve_action = '';
                    
                     
                        $action = '<div class="inner-div"> 
                                    <a href="#" class="action-icon editRatingTypeCard" data-rating_id="' . $data->id . '"> <i class="mdi mdi-square-edit-outline"></i></a>
                                    <a href="#" class="action-icon deleteRatingType"  data-rating_id="' . $data->id . '"> <i class="mdi mdi mdi-delete"></i></a>
                                    </div>
                                    ';
                        return $action;
                    })
                    ->editColumn('take_review', function ($data) use ($request) {
                        $return = 'No';
                        if($data->is_take_reviews ==1){
                            $return = 'Yes';
                        }
                        return $return;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        
    }
    public function store(Request $request){

       
        try {
            $this->validate($request, [
              'rating_title'    => 'required|string|max:60'
            ]);
            DB::beginTransaction();
            $RatingTypes =  RatingTypes::where('id',$request->rating_type_id)->first() ?? new RatingTypes();
            $RatingTypes->title = $request->rating_title;
            $RatingTypes->is_enabled = 1;
            $RatingTypes->is_take_reviews = $request->is_take_reviews ?? 0;
            $RatingTypes->save();
            DB::commit();
            return $this->successResponse($RatingTypes, ' Rating types Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GiftCard  $promocode
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id){
        $GiftCard = RatingTypes::where('id', $id)->first();
        return response()->json(array('success' => true, 'data' => $GiftCard));
    }

    public function destroy($domain = '', $id){
        try{
            RatingTypes::where('id', $id)->delete();
            return response()->json([
                'status'=>'success',
                'message' => __('Rating types deleted successfully!'),
                'data' => []
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=>'error',
                'message' => $e->getCode(),
                'data' => []
            ]);
        }
        
    }
}
