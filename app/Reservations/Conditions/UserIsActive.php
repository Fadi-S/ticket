<?php


namespace App\Reservations\Conditions;


use App\Reservations\ConditionContract;
use App\Reservations\ConditionOutput;

class UserIsActive implements ConditionContract
{

    public function check($event, $user): ConditionOutput
    {
        if($user->isGuest()) {
            return ConditionOutput::undecided();
        }

        $message = __(':name\'s account must be activated to make reservations', ['name' => $user->locale_name]);
        if($user->isSignedIn())
            $message = __('Your account must be activated to make reservations');

        return $user->isActive()
            ? ConditionOutput::undecided()
            : ConditionOutput::deny()
                ->message($message);
    }
}
