<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\SmsProvider;
use DB;

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
            array(
                'id' => 1,
                'provider' => 'Twilio Service',
                'keyword' => 'twilio',
                'status' => '1'
            ),
            array(
                'id' => 2,
                'provider' => 'mTalkz Service',
                'keyword' => 'mTalkz',
                'status' => '1'
            ),
            array(
                'id' => 3,
                'provider' => 'Mazinhost Service',
                'keyword' => 'mazinhost',
                'status' => '1'
            ),
        );
        if($sms_count == 0)
        {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('sms_providers')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::table('sms_providers')->insert($maps);
        }else{
            foreach($maps as $map){
                $sms = SmsProvider::updateOrCreate([
                    'keyword' => $map['keyword']
                ],[
                    'provider' => $map['provider'],
                    'status' => $map['status'],
                ]);
            }
        } 
    }
}
