<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // For OPTIONS requests, respond immediately with 200 and CORS headers
        if ($request->getMethod() === 'options')
        {
            $response = service('response');
            $response->setHeader('Access-Control-Allow-Origin', '*')
                     ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
                     ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                     ->setStatusCode(200)
                     ->send();
            exit; // Stop further execution
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add CORS headers to all responses
        $response->setHeader('Access-Control-Allow-Origin', '*')
                 ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
                 ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
