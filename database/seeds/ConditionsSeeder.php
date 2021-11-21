<?php

use App\Models\Condition;
use App\Models\EventType;
use App\Reservations\Conditions\EnoughSpaceInEvent;
use App\Reservations\Conditions\EventDateHasNotPassed;
use App\Reservations\Conditions\HaveEventTickets;
use App\Reservations\Conditions\IsDeaconReservation;
use App\Reservations\Conditions\MustHaveFullName;
use App\Reservations\Conditions\MustHaveNationalID;
use App\Reservations\Conditions\NotAlreadyReserved;
use App\Reservations\Conditions\QualifiesForException;
use App\Reservations\Conditions\ReservedByAdmin;
use Illuminate\Database\Seeder;

class ConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $MustHaveNationalID = Condition::create([
            'name' => 'User must have national Id',
            'path' => MustHaveNationalID::class,
            'required' => false,
            'priority' => 1,
        ]);

        $MustHaveFullName = Condition::create([
            'name' => 'User must have full name',
            'path' => MustHaveFullName::class,
            'required' => false,
            'priority' => 1,
        ]);

        $EventDateHasNotPassed = Condition::create([
            'name' => 'Event must not have passed',
            'path' => EventDateHasNotPassed::class,
            'required' => true,
            'priority' => 1,
        ]);

        $NotAlreadyReserved = Condition::create([
            'name' => 'User must not have reserved in this event before',
            'path' => NotAlreadyReserved::class,
            'required' => true,
            'priority' => 1,
        ]);

        $ReservedByAdmin = Condition::create([
            'name' => 'Admin can add users overload to events',
            'path' => ReservedByAdmin::class,
            'required' => false,
            'priority' => 2,
        ]);

        $EnoughSpaceInEvent = Condition::create([
            'name' => 'Event must have enough places',
            'path' => EnoughSpaceInEvent::class,
            'required' => true,
            'priority' => 2,
        ]);

        $IsDeaconReservation = Condition::create([
            'name' => 'This event has deacons',
            'path' => IsDeaconReservation::class,
            'required' => false,
            'priority' => 3,
        ]);

        $QualifiesForException = Condition::create([
            'name' => 'Exception the day before event at 10pm',
            'path' => QualifiesForException::class,
            'required' => false,
            'priority' => 4,
        ]);

        $HaveEventTickets = Condition::create([
            'name' => 'Have tickets for event',
            'path' => HaveEventTickets::class,
            'required' => true,
            'priority' => 5,
        ]);

        // Mass
        $type = EventType::find(1);
        $type->setConditions([
            $MustHaveFullName,
            $EventDateHasNotPassed,
            $NotAlreadyReserved,
            $ReservedByAdmin,
            $EnoughSpaceInEvent,
            $IsDeaconReservation,
            $QualifiesForException,
            $HaveEventTickets,
        ]);

        // Vesper
        $type = EventType::find(4);
        $type->setConditions([
            $MustHaveFullName,
            $EventDateHasNotPassed,
            $NotAlreadyReserved,
            $ReservedByAdmin,
            $EnoughSpaceInEvent,
            $IsDeaconReservation,
            $QualifiesForException,
            $HaveEventTickets,
        ]);

        // Kiahk
        $type = EventType::find(2);
        $type->setConditions([
            $MustHaveFullName,
            $EventDateHasNotPassed,
            $NotAlreadyReserved,
            $ReservedByAdmin,
            $EnoughSpaceInEvent,
            $IsDeaconReservation,
            $QualifiesForException,
            $HaveEventTickets,
        ]);

        // baskha
        $type = EventType::find(3);
        $type->setConditions([
            $MustHaveFullName,
            $EventDateHasNotPassed,
            $NotAlreadyReserved,
            $ReservedByAdmin,
            $EnoughSpaceInEvent,
            $IsDeaconReservation,
            $QualifiesForException,
            $HaveEventTickets,
        ]);

        // Baskha Occasion
        $type = EventType::find(5);
        $type->setConditions([
            $MustHaveFullName,
            $EventDateHasNotPassed,
            $NotAlreadyReserved,
            $ReservedByAdmin,
            $EnoughSpaceInEvent,
            $IsDeaconReservation,
            $QualifiesForException,
            $HaveEventTickets,
        ]);
    }
}
