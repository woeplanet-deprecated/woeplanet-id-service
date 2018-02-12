<?php

namespace Woeplanet\Resolver\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class CORSHandler {
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next) {
        $next_response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', ['GET']);

        if ($request->isOptions()) {
            return $next_response;
        }

        return $next($request, $next_response);
    }
}

?>
