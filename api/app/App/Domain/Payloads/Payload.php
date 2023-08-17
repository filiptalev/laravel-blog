<?php

namespace App\App\Domain\Payloads;

use Illuminate\Pagination\LengthAwarePaginator;

abstract class Payload
{
    protected $data = null;

    protected $status = 200;

    protected $messages = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        $responseData = [
            'status' => "success",
            'type' => 'general_success',
            'code' => $this->status,
            'messages' => $this->messages,
            'data' => $this->data
        ];

        // If this is paginated include this in the response
        if ($this->data instanceof LengthAwarePaginator) {
            $responseData['pagination'] = [
                'total' =>  $this->data->total(),
                'per_page' =>  $this->data->perPage(),
                'current_page' =>  $this->data->currentPage(),
                'last_page' =>  $this->data->lastPage(),
                'from' =>  $this->data->firstItem(),
                'to' =>  $this->data->lastItem(),
                'previous_page_link' => $this->data->previousPageUrl(),
                'next_page_link' => $this->data->nextPageUrl(),
            ];
        }

        return $responseData;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getMainData()
    {
        return $this->data;
    }
}
