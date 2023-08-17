<?php

namespace App\Src\Domain\Services\User;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use Illuminate\Support\Facades\Hash;
use App\App\Domain\Payloads\ErrorPayload;
use App\Src\Domain\Resources\UserResource;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\ValidationPayload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;

class UpdateUserService implements ServiceInterface
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

        $id = Arr::pull($data, 'id');

        try {
            $user = $this->users->find($id);
        } catch (ModelNotFoundException $e) {
            return new ErrorPayload([trans('error-messages.resource_not_found')]);
        } catch (\Exception $e) {
            //log
            Log::error($e->getMessage(), ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_retrieving_error')]); //HTTP_EXPECTATION_FAILED
        }


        if (auth()->user()->id != $user->id && auth()->user()->role != 'administrator') {
            Log::error('User tried to update another user', ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_not_found')]);
        }


        try {
            DB::beginTransaction();

            if (isset($data['password'])) {
                if (!Hash::check($data['current_password'], $user->password))
                    return new ErrorPayload('Inccoorect current password', 422);
                unset($data['password_confirmation']);
                $data['password'] = Hash::make($data['password']);
            }

            $this->users->update($id, $data);

            DB::commit();
        } catch (\Exception $e) {
            //log
            DB::rollBack();
            Log::error($e->getMessage(), ['auth_user_id' => null]);
            return new ErrorPayload(['messsage' => [trans('error-messages.resource_not_created')]], 417); //HTTP_EXPECTATION_FAILED
        }

        $user = $this->users->findWhereFirst('id', $id);

        return new SuccessPayload(new UserResource($user));
    }

    protected function validate($data)
    {
        return validator($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:254',
            ],
            'password' => 'sometimes|min:3|max:30|confirmed',
            'current_password' => 'required_with:password|min:3|max:30',
        ]);
    }

   
}
