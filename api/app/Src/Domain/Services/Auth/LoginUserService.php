<?php

namespace App\Src\Domain\Services\Auth;

use App\App\Domain\ServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\App\Domain\Payloads\ErrorPayload;
use App\Src\Domain\Resources\UserResource;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\ValidationPayload;
use App\App\Domain\Payloads\UnauthenticatedPayload;

class LoginUserService implements ServiceInterface
{
    public function handle($data = [])
    {
        if (($validator = $this->validate($data))->fails()) {
            return new ValidationPayload($validator->getMessageBag());
        }

        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return new UnauthenticatedPayload(['message' => [trans('error-messages.credentials_mismatch')]]);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            return new ErrorPayload([trans('error-messages.user_not_active')]);
        }

        $token = $user->createToken('app-token')->plainTextToken;

        return new SuccessPayload([
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    protected function validate($data)
    {
        return validator($data, [
            'email' => 'required',
            'password' => 'required|min:3|max:30'
        ]);
    }
}
