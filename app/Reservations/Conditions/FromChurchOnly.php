<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class FromChurchOnly implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        $message = __(':name\'s account must be activated to make reservations', ['name' => $user->locale_name]);
        if($user->isSignedIn())
            $message = __('Your account must be activated to make reservations');

        return $user->church_id == 1
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()
                ->message($message);
    }
}
