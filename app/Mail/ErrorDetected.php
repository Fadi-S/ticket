<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laravel\Telescope\IncomingEntry;

class ErrorDetected extends Notification implements ShouldQueue
{
    use Queueable;

    private IncomingEntry $entry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(IncomingEntry $entry)
    {
        $this->entry = $entry;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $data = [
            'type' => $this->entry->type,
            'time' => $this->entry->recordedAt,
            'environment' => app()->environment(),
            'url' => app()->runningInConsole() ? 'CLI' : request()->method() . ' ' . request()->fullUrl(),
            'user' => $this->entry->content['user'] ?? null,
            'view_in_Telescope' => url('telescope/exceptions/' . $this->entry->uuid),
        ];

        return (new MailMessage)
            ->subject('Error detected in ' . config('app.name'))

            ->line('Error detected in ' . $data['url'] . ' at ' . $data['time'])

            ->line('by user ' . $data['user'] ? $data['user']['arabic_name'] : '-')

            ->level('error')

            ->action('View in telescope', $data['view_in_Telescope']);
    }
}
