<?php

namespace App\Src\Domain\Services\Auth;

use App\App\Domain\ServiceInterface;
use Illuminate\Support\Facades\Config;
use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\ValidationPayload;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;


class RecoverPasswordService implements ServiceInterface
{
    protected $users;

    public function __construct(EloquentUserRepository $users)
    {
        $this->users = $users;
    }

    public function handle($data = [])
    {
        if (($validator = $this->validate($data))->fails()) {
            return new ValidationPayload($validator->getMessageBag());
        }

        $password = $data['password'];

        $user = $this->users->newPasswordByResetToken($data['token'], $data['password']);

        if (!$user) {
            return new ErrorPayload([trans('error-messages.invalid_passord_reset_token')]);
        }

        return new SuccessPayload([trans('success-messages.password_changed')]);
    }

    protected function validate($data)
    {
        return validator($data, [
            'password' => 'required|min:3'
        ]);
    }
}
