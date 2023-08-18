<?php

namespace App\Src\Domain\Services\Blog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\App\Domain\ServiceInterface;
use Illuminate\Support\Facades\Storage;
use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\SuccessPayload;
use App\App\Domain\Payloads\ValidationPayload;
use App\Src\Domain\Repositories\Eloquent\EloquentBlogRepository;

class CreateBlogService implements ServiceInterface
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

        try {
            DB::beginTransaction();

            $blog = $this->blogs->create($data);

            if (isset($data['image']))
                $this->storeBlogImages($data['image'], $blog);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['auth_user_id' => auth()->user()->id]);
            return new ErrorPayload([trans('error-messages.resource_not_created')], 417); //HTTP_EXPECTATION_FAILED
        }

        return new SuccessPayload($blog);
    }

    public function validate($data)
    {
        return validator($data, [
            'title' => 'required',
            'body' =>  'required',
        ]);
    }
    public function storeBlogImages($image, $blog)
    {

        $imageFileName = time() . '_' . $image->getClientOriginalName();

        $image->storeAs('/images/blogs/' . $blog->id, $imageFileName);

        $blog->update([
            'image' => '/images/blogs/' . $blog->id . '/' . $imageFileName
        ]);

        return true;
    }
}
