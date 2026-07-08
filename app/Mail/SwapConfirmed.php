<?php

namespace App\Mail;

use App\Models\SwapRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SwapConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public SwapRequest $swapRequest, public User $recipient, public User $otherWorker)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your swap is confirmed! — JobSwap.lv',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.swap.confirmed',
            with: [
                'swapRequest' => $this->swapRequest,
                'otherWorker' => $this->otherWorker,
            ],
        );
    }
}
