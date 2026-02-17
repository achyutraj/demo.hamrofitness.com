<?php

namespace App\Jobs;

use App\Models\CustomerEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCustomerEmail implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels,Queueable;

    protected $email, $view;

    public function __construct(CustomerEmail $email, $view)
    {
        $this->email = $email;
        $this->view  = $view;
    }

    public function handle()
    {
        $email = $this->email;
        Mail::send(
            $this->view,
            array(
                'email' => $email
            ),
            function ($m) use ($email) {
                //$m->from($email->sender->email, $email->sender->username);
                $m->subject($email->subject);
                $m->to($email->recipient->email, $email->recipient->first_name.' '.$email->recipient->last_name);
            }
        );
        $email->status = 1;
        $email->save();
    }
}
