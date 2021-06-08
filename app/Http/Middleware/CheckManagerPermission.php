<?php

namespace App\Http\Middleware;

use App\Model\Client;
use Illuminate\Support\Facades\Cache;
use Request;
use Config;
use Illuminate\Support\Facades\DB;
use Redirect;
use Closure;
use URL;
class CheckGodPanel
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
      if (Auth::user()->is_superadmin == 0) {
          foreach (Auth::user()->getAllPermissions as $key => $value) {
              array_push($allowed, $value->permission->name);
          }
      
          if (Auth::user()->is_superadmin == 0 && $request->method() == 'GET') {
              $currentPath = \Request::path();
              if ($currentPath == "profile") {
              } else {
                  $sub_admin_per = false;
                  $check_url = Request::path();
                  $per_url = explode('/', $check_url);
                
                  foreach (Auth::user()->getAllPermissions as $key => $value) {
                      if (strtolower($value->name) == strtolower($per_url['0'])) {
                          $sub_admin_per = true;
                      }
                  }

                  if ($sub_admin_per == false) {
                      return Redirect::route('profile.index');
                  }
              }
          }
      }
}
