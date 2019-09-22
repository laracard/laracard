<?php

namespace App\Traits;

Trait ApiResponse
{
    public $autoMsg = 1;
    private $responseData = [
        'status' => 0,
        'msg' => '',
        'data' => [],
        'auto_msg' => true,
    ];

    public function error(string $msg, int $status = 1, array $data = [])
    {
        $this->responseData['msg'] = $msg;
        $this->responseData['status'] = $status;
        $this->responseData['data'] = $data;
        return response()->json($this->responseData);
    }

    public function success($data = [], $msg = '')
    {
        $this->responseData['msg'] = $msg;
        $this->responseData['status'] = 0;
        $this->responseData['data'] = $data;
        return response()->json($this->responseData);
    }

    public function notAutoMsg()
    {
        $this->responseData['auto_msg'] = false;
        return $this;
    }
}