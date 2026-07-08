<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostExpiringReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Post $post)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your JobSwap.lv post expires soon',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.post.expiring-reminder',
            with: ['post' => $this->post],
        );
    }
}
