<?php

namespace App\Mail;

use App\Models\EmployerApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployerApprovalRequested extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public EmployerApproval $approval)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action required: employee swap approval — JobSwap.lv',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.employer.approval-requested',
            with: [
                'approval' => $this->approval,
                'post' => $this->approval->post,
                'respondUrl' => route('employer.respond', ['token' => $this->approval->token]),
            ],
        );
    }
}
