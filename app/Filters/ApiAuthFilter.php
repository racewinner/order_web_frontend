<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AppActivation;

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
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Get POST data (can be JSON or form data)
        // RequestInterface in CodeIgniter 4 filters receives IncomingRequest which has these methods
        $postData = [];
        $jsonData = [];
        
        if (method_exists($request, 'getPost')) {
            $postData = $request->getPost() ?? [];
        }
        
        if (method_exists($request, 'getJSON')) {
            $jsonData = $request->getJSON(true) ?? [];
        }
        
        // Try to get from POST first, then JSON
        $user_serial = !empty($postData['user_serial']) ? $postData['user_serial'] : ($jsonData['user_serial'] ?? null);
        $user_activation = !empty($postData['user_activation']) ? $postData['user_activation'] : ($jsonData['user_activation'] ?? null);
        $client_client_ref = !empty($postData['client_client_ref']) ? $postData['client_client_ref'] : ($jsonData['client_client_ref'] ?? null);

        $response = service('response');
        $response->setContentType('application/json');

        // Check user_serial separately
        if (empty($user_serial)) {
            $response->setStatusCode(400);
            $response->setJSON([
                'success' => false,
                'message' => 'Missing required parameter: user_serial is required',
                'error' => 'missing_user_serial',
                'parameter' => 'user_serial'
            ]);
            return $response;
        }

        // Check user_activation separately
        if (empty($user_activation)) {
            $response->setStatusCode(400);
            $response->setJSON([
                'success' => false,
                'message' => 'Missing required parameter: user_activation is required',
                'error' => 'missing_user_activation',
                'parameter' => 'user_activation'
            ]);
            return $response;
        }

        // Check client_client_ref separately
        if (empty($client_client_ref)) {
            $response->setStatusCode(400);
            $response->setJSON([
                'success' => false,
                'message' => 'Missing required parameter: client_client_ref is required',
                'error' => 'missing_client_client_ref',
                'parameter' => 'client_client_ref'
            ]);
            return $response;
        }

        // Validate against app_activation table - check all three parameters together
        $appActivation = new AppActivation();
        $isValid = $appActivation->isValid($user_serial, $user_activation, $client_client_ref);
        
        if (!$isValid) {
            $response->setStatusCode(401);
            $response->setJSON([
                'success' => false,
                'message' => 'Invalid activation credentials. The provided user_serial, user_activation, and client_client_ref do not match any valid activation record.',
                'error' => 'invalid_activation'
            ]);
            return $response;
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
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}

