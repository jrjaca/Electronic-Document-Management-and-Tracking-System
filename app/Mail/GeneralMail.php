<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralMail extends Mailable
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
    //RouteController emailRouteStatus() function
    public function build()
    {
        // return $this->view('view.name');
        $data = $this->info;
        $from = 'EDMTS@philhealth.gov.ph';

        if ($data['caller_from'] = 'RouteReceived'){ //submitReceiving()
            return $this
                ->from($from)
                ->subject($data['subject'])
                ->view('email.route-details', compact('data'));  
                // ->with([
                //     'tracking_no' => $data['tracking_no']//,
                //     // 'total_amount' => $data['total_amount'],
                //     // 'tracking_code' => $data['tracking_code'],
                // ]);
        } elseif ($data['caller_from'] = 'RouteReleased'){
            return $this
                ->from($from)
                ->subject($data['subject'])
                ->view('email.route-details', compact('data'));
        } elseif ($data['caller_from'] = 'RouteTerminal'){
            return $this
                ->from($from)
                ->subject($data['subject'])
                ->view('email.route-details', compact('data'));
        }        
    }
}
