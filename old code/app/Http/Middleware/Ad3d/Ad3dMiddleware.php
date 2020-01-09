<?php

namespace App\Http\Middleware\Ad3d;

use App\Models\Ad3d\Staff\QcStaff;
use Closure;
use Illuminate\Http\Response;

class Ad3dMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //return $next($request);
        $modelStaff = new QcStaff();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) > 0) {
            if ($dataStaffLogin->level() <= 3) {
                return $next($request);
            } else {
                return redirect()->route('qc.work.home');
            }

        } else {
            //return new Response(view('manage.content.components.access-notify'));
            return new Response(view('ad3d.login.login'));
        }
    }
}
