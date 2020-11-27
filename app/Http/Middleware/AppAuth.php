<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use App\Model\BlockedToken;
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
        
        $tokenBlock = BlockedToken::where('token', $header['authorization'][0])->first();

        if($tokenBlock)
        {
            return response()->json(['error' => 'Invalid Session', 'message' => 'Session Expired'], 404);
            abort(404);
        }

        if (!Token::check($header['authorization'][0], 'secret'))
        {
            return response()->json(['error' => 'Invalid Session', 'message' => 'Session Expired'], 404);
            abort(404);
        }

        return $next($request);
        
    }
}