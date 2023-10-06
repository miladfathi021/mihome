<?php

namespace App\Http\Controllers;

class ApiController
{
    /**
     * @var int
     */
    public int $status = 200;

    /**
     * @param $data
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($data = null, $message = null) : \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $this->status);
    }

    /**
     * @param $data
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseOk($data = null, $message = null) : \Illuminate\Http\JsonResponse
    {
        return $this->setStatus(200)->response($data, $message);
    }

    /**
     * @param $status
     *
     * @return $this
     */
    public function setStatus($status) : static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param $data
     *
     * @return array
     */
    public function pagination($data)
    {
        return [
            'current' => $data->currentPage(),
            'last' => $data->lastPage(),
            'base' => $data->url(1),
            'next' => $data->nextPageUrl(),
            'prev' => $data->previousPageUrl()
        ];
    }
}
