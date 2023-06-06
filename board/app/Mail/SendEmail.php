<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct(User $user)
    public function __construct($user)
    {
        // return var_dump($user);
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->subject('메일 테스트')
        //             ->view('email.sendemail')
        //             ->with([
        //                 'user' => $this->user,
        //             ]);

        return $this->view('email.sendemail')
        ->subject('이메일 인증을 완료해주세요.')
        ->with([
                'name'              => $this->user->name,
                'email'             => $this->user->email,
                'verification_code' => $this->user->verification_code,
                'validityPeriod'    => $this->user->validity_period,
        ]);
    }

    // /**
    //  * Get the message envelope.
    //  *
    //  * @return \Illuminate\Mail\Mailables\Envelope
    //  */
    // public function envelope()
    // {
    //     return new Envelope(
    //         subject: '메일 테스트',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  *
    //  * @return \Illuminate\Mail\Mailables\Content
    //  */
    // public function content()
    // {
    //     return new Content(
    //         view: 'email.sendemail',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
