<?php

namespace App\Http\Controllers;

class SwaggerTestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/swaggertest",
     *     tags={"Swagger Test"},
     *     summary="Check API Swagger test",
     *
     *     @OA\Response(
     *         response=200,
     *         description="API is working"
     *     )
     * )
     */
    public function __invoke()
    {
        return response()->json(['status' => 'ok']);
    }
}
