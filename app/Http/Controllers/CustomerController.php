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
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['required'],
            'short_name'   => ['required'],
            'address'   => ['required'],
            'post_code'   => ['required'],
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all())->validate();
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];
        $customer = Customer::create($data);
        foreach ($request->short_name as $key => $value) {
            if(isset($value) && $value != null){
                $datas = [
                    'short_name' => $value,
                    'address'    => $request->address[$key],
                    'post_code'  => $request->post_code[$key],
                    'created_by' => $customer->id,
                ];
                $Loction = Location::create($datas);
            }
        }
        return redirect()->route('customer.index')->with('success', 'Customer Added successfully!');
      
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
        $customer = Customer::where('id',$id)->with('location')->first();
        return view('Customer/update-customer')->with(['customer'=>$customer]);
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
        $validator = $this->validator($request->all())->validate();
        $customer = Customer::find($id);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];

        $cost = Customer::where('id', $id)->update($data);
        $check = Location::where('created_by',$id)->delete();
        foreach ($request->short_name as $key => $value) {
            if(isset($value) && $value != null){
                $datas = [
                    'short_name' => $value,
                    'address'    => $request->address[$key],
                    'post_code'  => $request->post_code[$key],
                    'created_by' => $id,
                ];
                $Loction = Location::create($datas);
            }
            
           
        }
        return redirect()->route('customer.index')->with('success', 'Customer Updated successfully!');

        
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