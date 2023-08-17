<?php

namespace App\Src\Domain\Services\Auth;

use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use Illuminate\Support\Facades\Mail;
use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\ValidationPayload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;

class ForgotPasswordService implements ServiceInterface
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

        try {
            $user = $this->users->findWhereFirst('email', $data['email']);
        } catch (ModelNotFoundException $e) {
            return new ErrorPayload([trans('error-messages.resource_not_found')], 404);
        } catch (\Exception $e) {
            //log
            Log::error($e->getMessage(), ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_retreiving_error')], 417); //HTTP_EXPECTATION_FAILED
        }

        $token = $user->createPasswordRecoveryToken();

        Mail::to($user)->send(new PasswordReset($user, $token));

        return new SuccessPayload([
            'token' => $token
        ]);
    }

    protected function validate($data)
    {
        return validator($data,
        [
            'email' => 'required|email|exists:users,email'
        ],
        [
            'exists' => trans('error-messages.email_not_found')
        ]);
    }
}