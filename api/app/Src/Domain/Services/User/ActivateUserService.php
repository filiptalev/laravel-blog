<?php

namespace App\Src\Domain\Services\User;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\ValidationPayload;
use App\App\Domain\Payloads\UnauthorizedPayload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;

class ActivateUserService implements ServiceInterface
{
    protected $users;

    public function __construct(EloquentUserRepository $users)
    {
        $this->users = $users;
    }

    public function handle($data = [])
    {
        if (!(Gate::inspect('isAdmin', $this->users->entity()))->allowed()) {
            return new UnauthorizedPayload([trans('error-messages.resource_authorization_view')]);
        }

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

        try {
           $user =  $this->users->update($id, ['is_active' => 1]);
        } catch (\Exception $e) {
            //log
            Log::error($e->getMessage(), ['auth_user_id' => null]);
            return new ErrorPayload(['messsage' => [trans('error-messages.resource_not_activated')]], 417); //HTTP_EXPECTATION_FAILED
        }

        return new SuccessPayload($user);
    }

    protected function validate($data)
    {
        return validator($data, [
            'id' => 'required|exists:users,id'
        ]);
    }




}
