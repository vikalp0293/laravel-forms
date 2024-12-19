<?php

namespace App\Http\Controllers;

use OpenApi as OA;

class ApiBaseController extends Controller
{

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="L5 OpenApi",
     *      description="L5 Swagger OpenApi description"
     * )
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Demo API Server"
     * )
     * @OA\SecurityScheme(
     *   securityScheme="bearerAuth",
     *   type="http",
     *   scheme="bearer"
     * )
     *
     */

/**
 * @OA\Get(
 *     path="/",
 *     description="Home page",
 *     @OA\Response(response="default", description="Welcome page")
 * )
 */

    public function sendSuccessResponse($result = [], $code = 200, $token = '')
    {

        if (is_array($result) && count($result) == 0) {
            $result = (object) $result;
        }
        $response = [
            'success' => $result,
        ];
        if ($token && $token != '') {
            return response()->json($response, $code)->header('token', $token);
        }
        return response()->json($response, $code);
    }

    /*
     * function for send failure response
     */
    public function sendFailureResponse($message = 'Something went wrong.', $code = 422)
    {
        $response = [
            'error' => $message,
        ];

        return response($response, $code);
    }
}
