<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Agent;
use App\Model\AgentDocs;
use App\Model\TagsForAgent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;

class DriverRegistrationController extends Controller
{
    //
    public function storeAgent(Request $request)
    {
        //$validator = Validator::make($request->all());
        $getFileName = null;
        // $newtag = explode(",", $request->tags);
        // $tag_id = [];
        // foreach ($newtag as $key => $value) {
        //     if (!empty($value)) {
        //         $check = TagsForAgent::firstOrCreate(['name' => $value]);
        //         array_push($tag_id, $check->id);
        //     }
        // }
        if ($request->hasFile('upload_photo')) {
            $header = $request->header();
            if (array_key_exists("shortcode", $header)) {
                $shortcode =  $header['shortcode'][0];
            }
            $folder = str_pad($shortcode, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $folder = str_pad($shortcode, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $file = $request->file('upload_photo');
            $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
            $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
            $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
            $getFileName = $path;
        }
        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'vehicle_type_id' => $request->vehicle_type_id,
            'make_model' => $request->make_model,
            'plate_number' => $request->plate_number,
            'phone_number' => '+' . $request->country_code . $request->phone_number,
            'color' => $request->color,
            'profile_picture' => $getFileName != null ? $getFileName : 'assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg',
            'uid' => $request->uid,
            'is_approved' => 1
        ];
        // print_r($request->extra_keys);
        // dd();
        foreach ($request->extra_keys as $key => $value) {
            $keys = array_keys($value);
            $size = sizeof($value);
            if ($value[$keys[0]] == "text") {
                $files[$key] = [
                    'file_type' => $value[$keys[0]],
                    'agent_id' => $value[$keys[1]],
                    'file_name' => $value[$keys[2]],
                ];
            } else {
                // print_r($value[$keys[2]]->getClientOriginalName());
                // dd();
                $header = $request->header();
                if (array_key_exists("shortcode", $header)) {
                    $shortcode =  $header['shortcode'][0];
                }
                $folder = str_pad($shortcode, 8, '0', STR_PAD_LEFT);
                $folder = 'client_' . $folder;
                $file = $value[$keys[2]];
                if ($file != null) {
                    $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
                    $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
                    $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                    $getFileName = $path;
                    $files[$key] = [
                        'file_type' => $value[$keys[0]],
                        'agent_id' => $value[$keys[1]],
                        'file_name' =>  $path,
                    ];
                }
            }
            $agent_docs = AgentDocs::create($files[$key]);
        }
        $agent = Agent::create($data);
        if ($agent->wasRecentlyCreated && $agent_docs->wasRecentlyCreated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Agent created Successfully!',
                'data' => $agent, $agent_docs
            ]);
        }
    }
}
