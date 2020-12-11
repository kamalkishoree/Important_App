<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Customer;
use App\Model\TagCustomer;
use App\Model\Location;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::orderBy('created_at', 'DESC')->paginate(10);
        return view('Customer.customer')->with(['customers' => $customer]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Customer/add-customer');
    }

    /**
     * Validation method for agents data 
    */
    private function validationRules($id = '')
    {
        
        $rules = [
            'name' => "required|string|max:50",
            //'short_name'// => "required",
            //'address' => "required",
            //'post_code' => "required"
        ];
        if($id != ''){
            $rules['email'] = 'required|email|unique:customers,email,' . $id;
            $rules['phone_number'] = 'required|digits:10|unique:customers,phone_number,' . $id;
        }else{
            $rules['email'] = 'required|email|unique:customers,email';
            $rules['phone_number'] = 'required|digits:10|unique:customers,phone_number';
        }
        return $rules;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = $this->validationRules();
        $validation  = Validator::make($request->all(), $rule)->validate();
        //$validator = $this->validator($request->all())->validate();
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];

        //dd($request->all());
        $customer = Customer::create($data);
        foreach ($request->short_name as $key => $value) {
            if(isset($value) && $value != null){
                $datas = [
                    'short_name' => $value,
                    'address'    => (!empty($request->address[$key])) ? $request->address[$key] : 'unnamed',
                    'post_code'  => $request->post_code[$key],
                    'latitude'    => $request->latitude[$key],
                    'longitude'  => $request->longitude[$key],
                    'created_by' => $customer->id,
                ];
                $Loction = Location::create($datas);
            }
        }

        if($customer->wasRecentlyCreated){
            return response()->json([
                'status'=>'success',
                'message' => 'Customer created Successfully!',
                'data' => $customer
            ]);
        }
        //return redirect()->route('customer.index')->with('success', 'Customer Added successfully!');
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //echo $id.'fff';
        $customer = Customer::where('id', $id)->with('location')->first();
        //dd($customer->toArray());
        $returnHTML = view('Customer.form')->with('customer', $customer)->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML, 'addFieldsCount'=> $customer->location->count()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rule = $this->validationRules($id);
        $customer = Customer::find($id);
        $validation  = Validator::make($request->all(), $rule)->validate();
        //$validator = $this->validator($request->all())->validate();
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];

        $customer->update($data);
        //$check = Location::where('created_by',$id)->delete();
        foreach ($request->short_name as $key => $value) {

            if(isset($value) && $value != null){

                if(array_key_exists($key, $request->location_id)){
                    $location = Location::find($request->location_id[$key]);
                    if($location){
                        $location->short_name = $value;
                        $location->address = $request->address[$key];
                        $location->post_code = $request->post_code[$key];
                        $location->latitude = $request->latitude[$key];
                        $location->longitude = $request->longitude[$key];

                        $location->save();
                    }
                }else{

                    $datas = [
                        'short_name' => $value,
                        'address'    => (!empty($request->address[$key])) ? $request->address[$key] : 'unnamed',
                        'post_code'  => $request->post_code[$key],
                        'latitude'    => $request->latitude[$key],
                        'longitude'  => $request->longitude[$key],
                        'created_by' => $customer->id,
                    ];
                    $Loction = Location::create($datas);

                }
            }
        }

        if($customer){
            return response()->json([
                'status'=>'success',
                'message' => 'Customer updated Successfully!',
                'data' => $customer
            ]);
        }
        //return redirect()->route('customer.index')->with('success', 'Customer Updated successfully!');

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Customer::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Customer deleted successfully!');
    }

    public function changeStatus(Request $request)
    {
       
        $customer = Customer::find($request->id);
        $customer->status = $request->status;
        $customer->save();
  
        return response()->json(['success'=>'Status change successfully.']);
    }
}