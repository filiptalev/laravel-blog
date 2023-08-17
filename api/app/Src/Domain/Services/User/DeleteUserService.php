<?php

namespace App\Src\Domain\Services\User;

use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use Illuminate\Support\Facades\Gate;
use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\ValidationPayload;
use App\App\Domain\Payloads\UnauthorizedPayload;
use App\Src\Domain\Models\User;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;

class DeleteUserService implements ServiceInterface
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
            $user = $this->users->findWhereFirst('id', $data['id']);
        } catch (\Exception $e) {
            //log
            Log::error($e->getMessage(), ['auth_user_id' => null]);
            return new ErrorPayload(['message' => [trans('error-messages.resource_not_found')]]); //HTTP_EXPECTATION_FAILED
        }

        if (!(Gate::inspect('isAdmin', $this->users->entity()))->allowed()) {
            return new UnauthorizedPayload([trans('error-messages.resource_authorization_view')]);
        }

        try {
            $user->update([
                'is_active' => 0
            ]);
        } catch (\Exception $e) {
            //log
            Log::error($e->getMessage(), ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_retrieving_error')]); //HTTP_EXPECTATION_FAILED
        }

        return new SuccessPayload([trans('success-messages.resource_success_delete')]);
    }

    protected function validate($data)
    {
        return validator($data, [
            'id' => 'required|exists:users|numeric',
        ]);
    }
}
