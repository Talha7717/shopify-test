<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    // You must disable CSRF as there is currently no solution for verifying session tokens with CSRF, there is a conflict due to new login creation each request.
    // for shopify
    
    protected $except = [
        '*',
    ];
}
