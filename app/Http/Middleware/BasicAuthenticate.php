<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $AUTH_USER    = 'admin';
        $AUTH_PASS    = '12345678';

        $HEADER_KEY   = 'TEST-API';
        $HEADER_VALUE = 'abcdzxcvbn';

        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );
        
        if ($is_not_authenticated || $request->header('key') != $HEADER_KEY || $request->header('value') != $HEADER_VALUE) {
            return response('Authentication Failed !', 401, ['WWW-Authenticate' => 'Basic']);
        }

        return $next($request);
    }
}
