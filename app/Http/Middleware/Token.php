<?php

namespace App\Http\Middleware;

use App\Models\CallApi;
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
            return response()->json(['data' => ['message' => 'token not find.']], 400);
        }

        $data = User::where('token', $token)->first(['id', 'token']);

        if ($data) {
            $ip = $request->ip();
            $id = $data->id;
            $user_agent = $request->header('user-agent');

            if ($data == null) {
                return response()->json(['data' => ['message' => 'Token invalid.']], 400);
            } else if ($data->token !== $token) {
                return response()->json(['data' => ['message' => 'Token invalid.']], 400);
            }
            $request['user_id'] = $id;

            $data = new CallApi;
            $data->user_id = $id;
            $data->ip = $ip;
            $data->user_agent = $user_agent;
            $data->created_at = now();
            $data->save();

            return $next($request);
        }
        return response()->json(['data' => ['message' => 'Error server.']], 500);

    }
}
