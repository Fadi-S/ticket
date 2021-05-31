<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Laravel\Telescope\IncomingEntry;

class ErrorDetected extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

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

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.error-found', [
            'type' => $this->entry->type,
            'time' => $this->entry->recordedAt,
            'environment' => app()->environment(),
            'url' => app()->runningInConsole() ? 'CLI' : request()->method() . ' ' . request()->fullUrl(),
            'user' => $this->entry->content['user'] ?? null,
            'view_in_Telescope' => url('telescope/exceptions/' . $this->entry->uuid),
        ]);
    }
}
