<?php

namespace App\Src\Domain\Services\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use Illuminate\Support\Facades\Hash;
use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\SuccessPayload;
use App\Src\Domain\Services\Enums\UserRole;
use App\App\Domain\Payloads\ValidationPayload;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;

class RegisterUserService implements ServiceInterface
{
    protected $users;

    public function __construct(EloquentUserRepository $users)
    {
        $this->users = $users;
    }

    public function handle($data = [])
    {
        DB::beginTransaction();


        if (($validator = $this->validate($data))->fails()) {
            return new ValidationPayload($validator->getMessageBag());
        }

        unset($data['password_confirmation']);

        $data['new_password'] = $data['password'];

        $data['password'] = Hash::make($data['password']);

        try {
            $user = $this->users->create($data);
        } catch (\Exception $e) {
            //log
            Log::error($e->getMessage(), ['auth_user_id' => null]);

            DB::rollBack();
            return new ErrorPayload(['messsage' => [trans('error-messages.resource_not_created')]], 417); //HTTP_EXPECTATION_FAILED
        }


        $token = $user->createToken('app-token')->plainTextToken;

        DB::commit();

        return new SuccessPayload([
            'user' => $user,
            'token' => $token
        ]);
    }

    protected function validate($data)
    {
        return validator($data, [
            'email' => [
                'required',
                'email',
                'max:254',
                'unique:users,email'
            ],
            'first_name' => 'required|max:254',
            'last_name' => 'required|max:254',
            'password' => 'required|min:3|max:30|confirmed',
            'password_confirmation' => 'required|same:password',
            'role' => [
                'required',
                Rule::in(UserRole::getConstantsWithouAdmin()),
            ]
        ]);
    }
}
