<?php

namespace hispanicus\Http\Middleware;

use Closure;
use hispanicus\AppCode;
class checkUuid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('uuid')) {
            $code = AppCode::where('device_id', '=', $request->header('uuid'))
            ->get()->first();
            if ($code) {
                return $next($request);
            }
        }
        return response()->json(["fail" => "unauthorized"], 401);
    }
}
