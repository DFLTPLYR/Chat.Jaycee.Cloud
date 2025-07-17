<?php

namespace App\Http\Middleware;

use Closure;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InputSanitizerMiddleware
{
    protected HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,b,a[href],i,em,strong,ul,ol,li,br');
        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Recursively sanitize input array using HTMLPurifier.
     */
    public function sanitize(array $input): array
    {
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = $this->sanitize($value);

            } elseif (is_string($value)) {
                $input[$key] = $this->purifier->purify($value);
            }
        }

        return $input;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cleaned = $this->sanitize($request->all());
        $request->merge($cleaned);

        return $next($request);
    }
}
