<?php

namespace App\Mail;

use App\Models\SwapRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public SwapRequest $swapRequest)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We could not process your swap payment — JobSwap.lv',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.swap.payment-failed',
            with: ['swapRequest' => $this->swapRequest],
        );
    }
}
