<?php

namespace App\Http\Middleware\Work;

use App\Models\Ad3d\Staff\QcStaff;
use Closure;

class CheckWorkLogin
{
    /**
     * Handle an incoming request.
     *qc.work.home
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $dataStaffLogin = $modelStaff->loginStaffInfo();

        if ($hFunction->checkCount($dataStaffLogin)) {
            return $next($request);
        } else {
            return redirect()->route('qc.work.login.get');
        }
        //return $next($request);
    }
}
