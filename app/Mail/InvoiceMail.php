<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $filename;
    public $ticket;

    /**
     * Create a new message instance.
     */
    public function __construct($pdf, $filename, $ticket)
    {
        $this->pdf = $pdf;
        $this->filename = $filename;
        $this->ticket = $ticket;
    }

    public function build()
    {

        return $this->subject('Votre facture')
            ->view('invoice')->with([
                'ticket' => $this->ticket,
            ])
            ->attachData($this->pdf, $this->filename, [
                'mime' => 'application/pdf',
            ]);
    }


    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Invoice Mail',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
