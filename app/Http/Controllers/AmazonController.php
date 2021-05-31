<?php

namespace App\Http\Controllers;

use App\Models\EmailBlacklist;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

class AmazonController extends Controller
{
    public function handle(MessageValidator $validator)
    {
        $message = Message::fromRawPostData();
        if(! $validator->isValid($message)) {
            abort(404);
        }

        $message = collect($message->toArray());

        if ($message->get('Type') === 'SubscriptionConfirmation') {
            \Http::get( $message->get('SubscribeURL') );

            return response()->json(['status' => 200, "message" => 'AWS subscription confirmed']);
        }

        if ($message->get('Type') === 'Notification') {
            $message = $message->get('Message');

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

                abort(404);
            }

            foreach ($type['recipients'] as $recipients) {
                $emailAddress = $recipients['emailAddress'];

                $email = EmailBlacklist::firstOrCreate(['email' => $emailAddress, 'problem_type' => 'Bounce']);
                if ($email) {
                    $email->increment('repeated_attempts', 1);
                }
            }

            return response()->json(['message' => 'Notification handled']);
        }

        return response([], 404);
    }
}
