<?php

namespace App\Jobs;

use form;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormCreatedNotification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFormCreatedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
           
           $recipientEmail = 'rahulrgm6@gmail.com'; // Replace with actual recipient email
           $formName = $this->form->name;
   
           Mail::to($recipientEmail)->send(new \App\Mail\FormCreatedNotification($formName));
    }
}
