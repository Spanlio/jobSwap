<?php

namespace App\Mail;

use App\Models\SwapRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SwapDeclinedByEmployer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public SwapRequest $swapRequest)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A swap request could not proceed — JobSwap.lv',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.swap.declined-by-employer',
            with: ['swapRequest' => $this->swapRequest],
        );
    }
}
