<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($response) {
        
        $response = [
            'success' => $response['success'] ?? true,
            'data' => $response['data'] ?? [],
            'message' => $response['message'] ?? '',
            'code' => $response['code'] ?? 200
        ];

        return response()->json($response, $response['code']);
    }
}
