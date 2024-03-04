<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $info;
    
    public function __construct($info)
    {
        $this->info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    //DocumentController saveNewDocument() submitOrUpdateDraftDocument() function
    public function build()
    {
        $data = $this->info;
        $from = 'EDMTS@philhealth.gov.ph';

        return $this
            ->from($from)
            ->subject($data['subject'])
            ->view('email.create_document-details', compact('data'));   //CREATE invoice VIEW inside frontend.mail folder
            // ->with([
            //     'tracking_no' => $data['tracking_no']//,
            //     // 'total_amount' => $data['total_amount'],
            //     // 'tracking_code' => $data['tracking_code'],
            // ]);
    }
}
