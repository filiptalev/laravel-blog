<?php

namespace App\Src\Domain\Services\Blog;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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

class UpdateBlogService implements ServiceInterface
{
    protected $blogs;

    public function __construct(EloquentBlogRepository $blogs)
    {
        $this->blogs = $blogs;
    }

    public function handle($data = [])
    {
        $id = Arr::pull($data, 'id');

        try {
            $blog = $this->blogs->findWhereFirst('id', $id);
        } catch (ModelNotFoundException $e) {
            return new ErrorPayload([trans('error-messages.resource_not_found')], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_retreiving_error')], 417); //HTTP_EXPECTATION_FAILED
        }

        if (($validator = $this->validate($data, $id))->fails()) {
            return new ValidationPayload($validator->getMessageBag());
        }

        if (!Gate::allows('update', $blog)) {
            return new UnauthorizedPayload([trans('error-messages.resource_authorization_view')]);
        }

        try {
            DB::beginTransaction();

            if (isset($data['image'])) {
                $image = Arr::pull($data, 'image');
                $this->storeBlogImage($image, $blog);
            }

            $this->blogs->update($id, $data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage(), ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_update_error')], 417); //HTTP_EXPECTATION_FAILED
        }

        return new SuccessPayload($blog->fresh());
    }

    protected function validate($data)
    {
        return validator($data, [
            'title' => 'required',
            'body' =>  'required',
        ]);
    }

    public function storeBlogImage($image, $blog)
    {
        $path = '/images/blogs/' . $blog->id;

        if (Storage::exists($path)) {
            Storage::deleteDirectory($path);
        }
        
        $imageFileName = time() . '_' . $image->getClientOriginalName();

        $image->storeAs('/images/blogs/' . $blog->id, $imageFileName);

        $this->blogs->update($blog->id, [
            'image' => '/images/blogs/' . $blog->id . '/' . $imageFileName
        ]);
    }
}
