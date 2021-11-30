<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\OtpCode;

class OtpVerification extends Mailable
{
    use Queueable, SerializesModels;

    protected $otp_code;
    protected $user_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OtpCode $otp, $name)
    {
        $this->otp_code = $otp;
        $this->user_name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.otp-verification')
                    ->with([
                        'otp_code' => $this->otp_code->otp_code,
                        'user_name' => $this->user_name
                    ]);
    }
}
