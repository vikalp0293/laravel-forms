<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Helpers;
use Modules\Ecommerce\Entities\PaymentCollection;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;


class SendSuccessNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $booking_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($booking_id)
    {
        $this->booking_id = $booking_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Helpers::sucessfulPaymentMail($this->booking_id);
    }
}
