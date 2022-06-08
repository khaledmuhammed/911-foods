<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd($this->data['image']);
        if($this->data['market_name'] == null){
            // users email
            if($this->data['image']){
                return $this->view('email')
                ->subject('A new contact email')
                ->from('info@911-foods.com')
                ->attach('public/images/' . $this->data['image'])
                ->with('data', $this->data);
            }
            return $this->view('email')
                ->subject('A new contact email')
                ->from('info@911-foods.com')
                ->with('data', $this->data);
        }
        // markets email
        return $this->view('market_email')
        ->subject('A new market contact email')
        ->from('info@911-foods.com')
        ->with('data', $this->data);
    }
}
