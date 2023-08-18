<?php

namespace App\Src\Domain\Services\Blog;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\UnauthorizedPayload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Src\Domain\Repositories\Eloquent\EloquentBlogRepository;

class ShowBlogService implements ServiceInterface
{
    protected $blogs;

    public function __construct(EloquentBlogRepository $blogs)
    {
        $this->blogs = $blogs;
    }

    public function handle($data = [])
    {

        try {
            $blog = $this->blogs->findWhereFirst('id', $data['id']);

            if (!Gate::allows('show', $blog)) {
                return new UnauthorizedPayload([trans('error-messages.resource_authorization_view')]);
            }
        } catch (ModelNotFoundException $e) {
            return new ErrorPayload([trans('error-messages.resource_not_found')], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_retreiving_error')], 417); //HTTP_EXPECTATION_FAILED
        }

        return new SuccessPayload($blog);
    }
}
