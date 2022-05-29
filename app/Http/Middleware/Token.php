<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('token');
        if ($token == null) {
            return response()->json(['data' => ['message' => 'token not find.']]);
        }

        $data = User::where('token', $token)->first(['id', 'token']);
        if ($data == null) {
            return response()->json(['data' => ['message' => 'Token invalid.']], 400);
        } else if ($data->token !== $token) {
            return response()->json(['data' => ['message' => 'Token invalid.']], 400);
        }
        $request['user_id'] = $data->id;
        return $next($request);
    }
}
