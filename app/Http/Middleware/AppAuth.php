<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use App\Model\{BlockedToken, Agent};
use Illuminate\Support\Facades\Cache;
use Request;
use Config;
use Illuminate\Support\Facades\DB;
use JWT\Token;

class AppAuth
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
        $header = $request->header();

        $token = $header['authorization'][0];

        if (!Token::check($token, 'secret'))
        {
            return response()->json(['error' => 'Invalid Token', 'message' => 'Session Expired'], 404);
            abort(404);
        }
        
        $tokenBlock = BlockedToken::where('token', $token)->first();

        if($tokenBlock)
        {
            return response()->json(['error' => 'Invalid Session', 'message' => 'Session Expired'], 404);
            abort(404);
        }

        $agent = Agent::where('access_token', $token)->first();

        if($tokenBlock)
        {
            return response()->json(['error' => 'Invalid Session', 'message' => 'Invalid Token or session has been expired.'], 404);
            abort(404);
        }

        return $next($request);
        
    }
}