<?php

namespace App\Src\Domain\Services\Blog;

use Exception;
use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\ValidationPayload;
use App\App\Domain\Payloads\UnauthorizedPayload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Src\Domain\Repositories\Eloquent\EloquentBlogRepository;

class DeleteBlogService implements ServiceInterface
{
    protected $blogs;

    public function __construct(EloquentBlogRepository $blogs)
    {
        $this->blogs = $blogs;
    }


    public function handle($data = [])
    {
        if (($validator = $this->validate($data))->fails()) {
            return new ValidationPayload($validator->getMessageBag());
        }

        if (!Gate::allows('delete', $this->blogs->find($data['id']))) {
            return new UnauthorizedPayload([trans('error-messages.resource_authorization_view')]);
        }

        try {
            $this->deleteStorageImages($data['id']);
            $this->blogs->delete($data['id']);
        } catch (ModelNotFoundException $e) {
            return new ErrorPayload([trans('error-messages.resource_not_found')], 404);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_deleting_error')], 417); //HTTP_EXPECTATION_FAILED
        }

        return new SuccessPayload([trans('success-messages.blog_deleted')]);
    }

    public function validate($data)
    {
        return validator($data, [
            'id' => 'required|integer|exists:blogs,id'
        ]);
    }

    public function deleteStorageImages($id)
    {
        $path = '/images/blogs/' . $id;

        if (Storage::exists($path)) {
            Storage::deleteDirectory($path);
        }
    }
}
