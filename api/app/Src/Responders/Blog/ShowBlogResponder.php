<?php

namespace App\Src\Responders\Blog;

use App\App\Domain\Responders\Responder;
use App\Src\Domain\Resources\BlogResource;
use App\App\Domain\Responders\ResponderInterface;

class ShowBlogResponder extends Responder implements ResponderInterface
{
    public function respond()
    {
        if ($this->hasErrorsPayload($this->response)) {
            return response()->json($this->response->getData(), $this->response->getStatus());
        }

        $data = $this->response->getData();

        $data['data'] = new BlogResource($data['data']);

        return $data;
    }
}
