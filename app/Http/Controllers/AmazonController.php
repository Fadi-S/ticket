<?php

namespace App\Http\Controllers;

use App\Models\EmailBlacklist;
use Illuminate\Http\Request;

class AmazonController extends Controller
{
    public function handle(Request $request)
    {
        $notifications = collect(json_decode(\Cache::get('notifications', '[]')));
        $notifications->push($request->toArray());
        \Cache::put('notifications', $notifications->toJson());

        if ($request->json('Type') == 'Notification' || $request->json('Type') == 'SubscriptionConfirmation') {
            $message = $request->json('Message');

            $type = [
                'Bounce' => [
                    'name' => 'bounce',
                    'recipients' => 'bouncedRecipients',
                ],
                'Complaint' => [
                    'name' => 'complaint',
                    'recipients' => 'complainedRecipients',
                ],
            ][$message['notificationType']] ?? [];

            if(! $type) {
                $this->handle($message);

                return response()->json(['status' => 200, "message" => 'success']);
            }

            foreach ($type['recipients'] as $recipients) {
                $emailAddress = $recipients['emailAddress'];

                $email = EmailBlacklist::firstOrCreate(['email' => $emailAddress, 'problem_type' => 'Bounce']);
                if ($email) {
                    $email->increment('repeated_attempts', 1);
                }
            }
        }

        return response()->json(['status' => 200, "message" => 'success']);
    }

    private function defaultHandler($message)
    {

    }
}
