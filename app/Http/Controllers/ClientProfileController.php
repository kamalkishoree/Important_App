<?php

namespace App\Http\Controllers;

use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Jobs\UpdatePassword;

class ClientProfileController extends Controller
{

    public function edit($id)
    {

        $client = Client::find($id);
        return view('godpanel/update-client')->with('client', $client);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['required'],
            'company_name' => ['required'],
            'company_address' => ['required'],
            'custom_domain' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update');
        }


        $getClient = Client::find($id);
        $getFileName = $getClient->logo;

        // Handle File Upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $fileNameToStore = $filename . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/clients', $fileNameToStore);
            $getFileName = $fileNameToStore;
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'custom_domain' => $request->custom_domain,
            'country' => $request->country ? $request->country : NULL,
            'timezone' => $request->timezone ? $request->timezone : NULL,
            'logo' => $getFileName,
        ];

        $client = Client::where('id', $id)->update($data);

        return redirect()->back()->with('success', 'Profile Updated successfully!');
        // return redirect()->route('client.index')->with('success', 'Profile Updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $id = Auth::user()->id;

        $user = Client::where('id', 1)->first();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (Hash::check($request->old_password, $user->password)) {
            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            $password = Hash::make($request->password);
            $this->dispatchNow(new UpdatePassword($password));
            $request->session()->flash('success', 'Password changed');
            return redirect()->back()->with('success', 'Password Changed successfully!');
        } else {
            $request->session()->flash('error', 'Wrong Old Password');
            return redirect()->back();
        }
    }
}
