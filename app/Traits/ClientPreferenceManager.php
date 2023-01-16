<?php
namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Model\{ClientPreference,ClientPreferenceAdditional,Client};
use GuzzleHttp\Client as GCLIENT;
use Log;
use Storage;

trait ClientPreferenceManager{

  public $client_preference_fillable_key = [ 'pickup_type', 'drop_type'];

  /**
   * updatePreferenceAdditional
   *
   * @param  mixed $$request
   * @return void
   * harbans :)
   *
   */
  public function updatePreferenceAdditional($request=[]){
    $validated_keys = $request->only($this->client_preference_fillable_key);
    // dd($validated_keys);
    $client = Client::first();
    foreach($validated_keys as $key => $value){
        ClientPreferenceAdditional::updateOrCreate(
            ['key_name' => $key, 'client_code' => $client->code],
            ['key_name' => $key, 'key_value' => $value,'client_code' => $client->code,'client_id'=> $client->id]);
    }
    return 1;
  }


}
