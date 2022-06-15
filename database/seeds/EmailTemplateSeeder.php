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
                'tags' => '{agent_name}, {title}, {description}, {email}, {phone_no}, {address},{website}', 
                'content' => '<tbody><tr><td><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Name</h4><p>{agent_name}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Title</h4><p>{title}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Description</h4><p>{description}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Email</h4><p>{email}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Phone Number</h4><p>{phone_no}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Address</h4><address style="font-style: normal;"><p style="width: 300px;">{address}</p></address></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Website</h4><a style="color: #8142ff;" href="{website}" target="_blank"><b>{website}</b></a></div></td></tr></tbody>'
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
                      'id' => $option['id'],
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
