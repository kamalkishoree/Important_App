<?php

use Illuminate\Database\Seeder;
use App\Model\{EmailTemplate};

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $option_count = DB::table('email_templates')->count();

        $options = [
            [
                'label' =>'New Agent Signup',
                'subject' =>'New Agent Signup',
                'tags' => '{agent_name}, {phone_no}, {team}', 
                'content' => ''
            ]
        ];

        if($option_count == 0)
        {
          DB::statement('SET FOREIGN_KEY_CHECKS=0;');
          DB::table('email_templates')->truncate();
          DB::statement('SET FOREIGN_KEY_CHECKS=1;');
  
          DB::table('email_templates')->insert($options);
        }
        else{
            foreach ($options as $option) {
                $ops = EmailTemplate::where('label', $option['label'])->first();
   
                if ($ops !== null) {
                    // $ops->update(['label' => $option['label']]);
                } else {
                    $ops = EmailTemplate::create([
                      'label' => $option['label'],
                      'subject' => $option['subject'],
                      'tags' => $option['tags'],
                      'content' => $option['content'],
                    ]);
                }
            }
        }
    }
}
