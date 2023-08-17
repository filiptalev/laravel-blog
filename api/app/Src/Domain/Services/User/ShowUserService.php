<?php

namespace App\Src\Domain\Services\User;

use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use App\App\Domain\Payloads\ErrorPayload;
use App\Src\Domain\Resources\UserResource;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\ValidationPayload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;

class ShowUserService implements ServiceInterface
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
        } catch (ModelNotFoundException $e) {
            return new ErrorPayload([trans('error-messages.resource_not_found')]);
        } catch (\Exception $e) {
            //log
            Log::error($e->getMessage(), ['Testimonials']);
            return new ErrorPayload([trans('error-messages.resource_retrieving_error')]); //HTTP_EXPECTATION_FAILED
        }

        return new SuccessPayload(new UserResource($user));
    }

    protected function validate($data)
    {
        return validator($data, [
            'id' => 'required|exists:users|numeric'
        ]);
    }
}
