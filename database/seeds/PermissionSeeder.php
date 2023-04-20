<?php

use Illuminate\Database\Seeder;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('permissions')->delete();
        $option_count = DB::table('permissions')->count();
 
        $types = array(
            array(
                'id' => 1,
                'name' => 'Dashboard'
            ),
            array(
                'id' => 2,
                'name' => 'Customers'
            ),
            array(
                'id' => 3,
                'name' => 'Routes'
            ),
            array(
                'id' => 4,
                'name' => 'Add Route'
            ),
            array(
                'id' => 5,
                'name' => 'Profile'
            ),
            array(
                'id' => 6,
                'name' => 'Customize'
            ),
            array(
                'id' => 7,
                'name' => 'Teams'
            ),
            array(
                'id' => 8,
                'name' => 'Agents'
            ),
            array(
                'id' => 9,
                'name' => 'Geo Fence'
            ),
            array(
                'id' => 10,
                'name' => 'Auto Allocation'
            ),
            array(
                'id' => 11,
                'name' => 'Pricing Rules'
            ),
            array(
                'id' => 12,
                'name' => 'Configure'
            ),
            array(
                'id' => 13,
                'name' => 'Analytics'
            ),
            array(
                'id' => 14,
                'name' => 'Notifications'
            ),
            array(
                'id' => 15,
                'name' => 'ACL'
            ),
            array(
                'id' => 16,
                'name' => 'Agent Threshold'
            )
        );
        if($option_count == 0)
        {
          DB::statement('SET FOREIGN_KEY_CHECKS=0;');
          DB::table('permissions')->truncate();
          DB::statement('SET FOREIGN_KEY_CHECKS=1;');
  
          DB::table('permissions')->insert($types);
        }
        else{
            foreach ($types as $type) {
                $permissions =DB::table('permissions')->where('id',$type['id'])->first();
   
                if (!$permissions) {
                    DB::table('permissions')->insert($type);
                }
            }
        }
       // DB::table('permissions')->insert($type);
    }
}
