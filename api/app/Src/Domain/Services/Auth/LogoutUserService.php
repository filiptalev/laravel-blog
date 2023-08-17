<?php

namespace App\Src\Domain\Services\Auth;

use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\SuccessPayload;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;


class LogoutUserService implements ServiceInterface
{
    protected $users;

    public function __construct(EloquentUserRepository $users)
    {
        $this->users = $users;
    }

    public function handle($data = [])
    {
        try {
            auth()->user()->tokens()->where('id', auth()->user()->currentAccessToken()->id)->delete();
        } catch (\RuntimeException $e) {
            Log::error("An error occured in {$e->getFile()} on line {$e->getLine()}, details:{$e->getMessage()}", ['auth_user_id' => null]);
            return new ErrorPayload([trans('error-messages.token_not_revoked')]);
        }

        return new SuccessPayload(['Logged out.']);
    }
}
