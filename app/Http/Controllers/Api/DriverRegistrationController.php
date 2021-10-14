<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Agent;
use App\Model\AgentDocs;
use App\Model\DriverRegistrationDocument;
use App\Model\TagsForAgent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverRegistrationController extends Controller
{
    //

    public function storeAgent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload_photo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|max:255',
            'phone_number' => 'required|unique:agents|min:9|max:15',
            
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                return response()->json(['status' => 0, "message" => $error_value[0]]);
            }
        }
        try {
            $getFileName = null;
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
                'phone_number' =>  '+' . $request->country_code . $request->phone_number,
                'color' => $request->color,
                'profile_picture' => $getFileName != null ? $getFileName : 'assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg',
                'uid' => $request->uid,
                'is_approved' => 1,
            ];
            $agent = Agent::create($data);
            $files = [];
            if ($request->hasFile('uploaded_file')) {
                $file = $request->file('uploaded_file');
                foreach ($request->uploaded_file as $key => $f) {
                    $header = $request->header();
                    if (array_key_exists("shortcode", $header)) {
                        $shortcode =  $header['shortcode'][0];
                    }
                    $folder = str_pad($shortcode, 8, '0', STR_PAD_LEFT);
                    $folder = 'client_' . $folder;
                    $path = [];
                    if (gettype($f) != "string") {
                        $file_name = uniqid() . '.' . $f->getClientOriginalExtension();
                        $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
                        $path = Storage::disk('s3')->put($s3filePath, $f, 'public');
                    }
                    foreach (json_decode($request->other) as $k => $o) {
                        $files[$k] = [
                            'file_type' => $o->file_type,
                            'agent_id' => $agent->id,
                            'file_name' => $path,
                            'label_name' => $o->filename1
                        ];
                    }
                    if (isset($files[$key])) {
                        $agent_docs = AgentDocs::create($files[$key]);
                    }
                }
            }
            foreach (json_decode($request->files_text) as $key => $f) {
                $files[$key] = [
                    'file_type' => $f->file_type,
                    'agent_id' => $agent->id,
                    'file_name' => $f->contents,
                    'label_name' => $f->label_name
                ];
                $agent_docs = AgentDocs::create($files[$key]);
            }

            if ($agent->wasRecentlyCreated && $agent_docs->wasRecentlyCreated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Thanks for signing up. We will get back to you shortly!',
                    'data' => $agent
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function sendDocuments()
    {
        try {
            $documents = DriverRegistrationDocument::orderBy('file_type','DESC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'Success!',
                'data' => $documents
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
