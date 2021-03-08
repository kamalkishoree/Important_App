<?php

use Illuminate\Database\Seeder;

class createTaskProof extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_proofs')->delete();
 
        $type = array(

            array(
                'id'                  => 1,
                'image'               => 1,
                'image_requried'      => 1,
                'signature'           => 1,
                'signature_requried'  => 1,
                'note'                => 1,
                'note_requried'       => 1,
                'type'                => 1
            ),
            array(
                'id'                  => 1,
                'image'               => 1,
                'image_requried'      => 1,
                'signature'           => 1,
                'signature_requried'  => 1,
                'note'                => 1,
                'note_requried'       => 1,
                'type'                => 2
            ),
            array(
                'id'                  => 1,
                'image'               => 1,
                'image_requried'      => 1,
                'signature'           => 1,
                'signature_requried'  => 1,
                'note'                => 1,
                'note_requried'       => 1,
                'type'                => 3
            )

        );
        
        DB::table('task_proofs')->insert($type);
    }
}
