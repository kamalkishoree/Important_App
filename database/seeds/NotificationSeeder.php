<?php

use Illuminate\Database\Seeder;
use App\Model\NotificationType;
use App\Model\NotificationEvent;
class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notification_events')->delete();
        DB::table('notification_types')->delete();

        $notification_types = NotificationType::create([
            'name' => 'Pickup Notifications'
        ]);

        // create all the events in the Pickup Notifications //
        $notification_types->notification_events()->createMany([
            ['name' => 'Agent Started'],
            ['name' => 'Agent Arrived'],
            ['name' => 'Successful'],
            ['name' => 'Failed']
        ]);

        $notification_types_d = NotificationType::create([
            'name' => 'Drop-off Notifications'
        ]);

        // create all the events in the drop-off Notifications //
        $notification_types_d->notification_events()->createMany([
            ['name' => 'Agent Started'],
            ['name' => 'Agent Arrived'],
            ['name' => 'Successful'],
            ['name' => 'Failed']
        ]);

        $notification_types_d = NotificationType::create([
            'name' => 'Appointment Notifications'
        ]);

        // create all the events in the drop-off Notifications //
        $notification_types_d->notification_events()->createMany([
            ['name' => 'Agent Started'],
            ['name' => 'Agent Arrived'],
            ['name' => 'Successful'],
            ['name' => 'Failed']
        ]);
    }
}
