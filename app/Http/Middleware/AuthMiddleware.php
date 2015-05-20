<?php namespace App\Http\Middleware;

use Closure;

class AuthMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('access_token');
        if ($token !== $_ENV['APP_KEY']) {
            return response()->json(
                [
                    'message'=>'Unauthorized',
                    'description'=>'Bad credentials,contact the administrator please'
                ], 401);
        }
        return $next($request);
    }
}
