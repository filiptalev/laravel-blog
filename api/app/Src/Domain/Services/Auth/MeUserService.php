<?php

namespace App\Src\Domain\Services\Auth;

use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use App\App\Domain\Payloads\ErrorPayload;
use App\Src\Domain\Resources\UserResource;
use App\App\Domain\Payloads\SuccessPayload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;

class MeUserService implements ServiceInterface
{
    protected $users;

    public function __construct(EloquentUserRepository $users)
    {
        $this->users = $users;
    }

    public function handle($data = [])
    {
        try {
            $userId = auth()->user()->id;
            $userRole = auth()->user()->role;

            $me = $this->users->getMe($userId, $userRole);
        } catch (ModelNotFoundException $e) {
            return new ErrorPayload([trans('error-messages.resource_not_found')]);
        } catch (\Exception $e) {
            //log
            Log::error($e->getMessage(), ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_retrieving_error')]); //HTTP_EXPECTATION_FAILED
        }

        return new SuccessPayload(new UserResource($me));
    }
}
