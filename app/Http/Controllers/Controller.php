<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function returnData($res, $status = 200, array $headers = [])
    {
        $headers = array_merge([
            'content-type' => 'application/json'
        ], $headers);
        return response([
            'data' => $res,
        ], $status, $headers);
    }

    protected function returnPaginate($content, $res)
    {
        return response([
            'data' => [
                'content' => $content,
                'total' => $res->total(),
                'page' => $res->currentPage(),
                'per_page' => $res->perPage(),
                'last_page' => $res->lastPage(),
            ],
        ]);
    }
}
