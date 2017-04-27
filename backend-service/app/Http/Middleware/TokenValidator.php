<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: TokenValidator.php
 * Date: 04/04/2017
 * Time: 21:29
 */

namespace App\Http\Middleware;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jobimarklets\Exceptions\AuthenticationException;

class TokenValidator
{
    public function handle(Request $req, \Closure $next)
    {
        //Check if the request has a token.
        if (!$req->hasHeader('api_token')) {
            Throw new AuthenticationException('No available token');
        }

        if (is_null(Cache::get($req->header('api_token')))) {
            throw new AuthenticationException('Token expired.');
        }

        return $next($req);
    }
}