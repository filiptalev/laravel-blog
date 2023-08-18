<?php
namespace App\Src\Responders\Blog;

use App\App\Domain\Responders\Responder;
use App\Src\Domain\Resources\BlogResource;
use App\App\Domain\Responders\ResponderInterface;

class IndexBlogPaginatedResponder extends Responder implements ResponderInterface
{
    public function respond()
    {
        //don't return the requested resource, return the erros
        if($this->hasErrorsPayload($this->response)) {
            return response()->json($this->response->getData(), $this->response->getStatus());
        }

        $data = $this->response->getData();

        $data['data'] = BlogResource::collection($data['data']);

        return $data;
    }
}
