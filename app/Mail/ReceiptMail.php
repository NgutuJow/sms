<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $receipt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment, $receipt)
    {
        $this->payment = $payment;
        $this->receipt = $receipt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payment Receipt - ' . $this->receipt->receipt_no)
                    ->markdown('emails.receipt');
    }
}
