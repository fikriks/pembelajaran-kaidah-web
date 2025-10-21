<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiAuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Get Authorization header
        $authorization = $request->getHeaderLine('Authorization');

        if (empty($authorization) || !preg_match('/Bearer\s+(.*)$/i', $authorization, $matches)) {
            return $this->respondWithError('Token tidak ditemukan', 401);
        }

        $token = $matches[1];

        try {
            $payload = json_decode(base64_decode($token), true);

            if (!$payload || !isset($payload['user_id']) || !isset($payload['exp'])) {
                return $this->respondWithError('Token tidak valid', 401);
            }

            // Check if token expired
            if ($payload['exp'] < time()) {
                return $this->respondWithError('Token sudah kadaluarsa', 401);
            }

            // Add user ID to request for later use
            $request->userId = $payload['user_id'];

        } catch (\Exception $e) {
            return $this->respondWithError('Token tidak valid', 401);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add CORS headers for mobile app
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        // Handle preflight requests
        if ($request->getMethod() === 'options') {
            $response->setStatusCode(200);
        }
    }

    /**
     * Send error response
     */
    private function respondWithError($message, $code = 400)
    {
        $response = service('response');
        $response->setStatusCode($code);
        $response->setJSON([
            'status' => 'error',
            'message' => $message
        ]);
        return $response;
    }
}