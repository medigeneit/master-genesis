<?php

namespace Medigeneit\MasterGenesis\Exceptions;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    public function register(): void
    {

        $this->renderable(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'exists' => false,
                    'message' => 'Resource not found.'
                ], 404);
            }
        },404);
    }
}
