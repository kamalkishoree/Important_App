<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Agent;
use App\Model\AgentDocs;
use App\Model\DriverRegistrationDocument;
use App\Model\TagsForAgent;
use App\Model\AgentsTag;
use App\Model\Team;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DriverRegistrationController extends Controller
{
    //
    public function validator(array $data)
    {


        $full_number = '';
        if (isset($data['country_code']) && !empty($data['country_code']) && isset($data['phone_number']) && !empty($data['phone_number']))
            $full_number = '+' . $data['country_code'] . $data['phone_number'];

        $data['phone_number'] = '+' . $data['country_code'] . $data['phone_number'];
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required'],
            'vehicle_type_id' => ['required'],
            //'make_model' => ['required'],
            //'plate_number' => ['required'],
            'phone_number' =>  ['required', 'min:9', 'max:15', Rule::unique('agents')->where(function ($query) use ($full_number) {
                return $query->where('phone_number', $full_number);
            })],
            //'color' => ['required'],
            'upload_photo' => ['mimes:jpeg,png,jpg,gif,svg|max:2048'],
        ]);
    }

    public function storeAgent(Request $request)
    {
        try {
            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                    return response()->json(['status' => 0, "message" => $error_value[0]]);
                }
            }
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

            $newtag = explode(",", $request->tags);
            $tag_id = [];
            foreach ($newtag as $key => $value) {
                if (!empty($value)) {
                    $check = TagsForAgent::firstOrCreate(['name' => $value]);
                    array_push($tag_id, $check->id);
                }
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
                'is_approved' => 0,
                'team_id' => $request->team_id == null ? $team_id = null : $request->team_id
            ];
            $agent = Agent::create($data);
            $agent->tags()->sync($tag_id);
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

            if ($agent->wasRecentlyCreated) {
                return response()->json([
                    'status' => 200,
                    'message' => __('Thanks for signing up. We will get back to you shortly!'),
                    'data' => $agent
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function sendDocuments()
    {
        try {
           
            $data['documents'] = DriverRegistrationDocument::orderBy('file_type', 'DESC')->get();
            $data['all_teams'] = Team::OrderBy('id','desc')->get();
            $data['agent_tags'] = TagsForAgent::OrderBy('id','desc')->get();
            return response()->json([
                'status' => 200,
                'message' => 'Success!',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
