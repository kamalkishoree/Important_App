<?php

use Illuminate\Database\Seeder;
use App\Model\SmsProvider;

class SmsProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sms_count = DB::table('sms_providers')->count();
        $maps = array(
            array('id' => 1, 'provider' => 'Twilio Service','keyword' => 'twilio','status' => '1' ),
            array('id' => 2, 'provider' => 'mTalkz Service', 'keyword' => 'mTalkz','status' => '1'),
            array('id' => 3, 'provider' => 'Mazinhost Service','keyword' => 'mazinhost','status' => '1'),
            array('id' => 4, 'provider' => 'Unifonic Service','keyword' => 'unifonic','status' => '1' ),
            array('id' => 5, 'provider' => 'Arkesel Service', 'keyword' => 'arkesel','status' => '1'),
            array('id' => 6, 'provider' => 'Vonage (nexmo)','keyword' => 'vonage', 'status' => '1'),
            array('id' => 7, 'provider' => 'SMS Partner France','keyword' => 'sms_partner', 'status' => '1'),
            array('id' => 8, 'provider' => 'Ethiopia', 'keyword' => 'ethiopia','status' => '1' )
        );
     
        if($sms_count == 0)
        {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('sms_providers')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::table('sms_providers')->insert($maps);
        }else{
            foreach($maps as $map){
                $first = SmsProvider::where('id',$map['id'] )->first();
                if($first){
                    $first->update(['provider'=>$map['provider'],'keyword'=>$map['keyword'] ]); 
                }else{
                    DB::table('sms_providers')->insert($map);
                }
                
            }
        }
    }
}
