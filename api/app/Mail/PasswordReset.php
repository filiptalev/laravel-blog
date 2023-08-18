<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Src\Domain\Models\User;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    private $user;
    /**
     * PasswordReset constructor.
     * @param User $user
     * @param $token
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('success-messages.auction_created_subject'))
            ->view('emails.password-reset')
            ->with([
                'name' => $this->user->first_name . ' ' . $this->user->last_name,
                'link' => config('app.cms_url') . '/reset-password/' . $this->token
            ]);
    }
}
