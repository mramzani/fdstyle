<?php

namespace Modules\Front\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerActivation
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        redirect()->setIntendedUrl($request->path());

        if (auth('customer')->check()) {
            if (auth('customer')->user()->status == "deActive") {
                $request->session()->flash('alertWarning', 'برای تجربه بهتر، لطفا اطلاعات حساب کابری خود را تکمیل نمایید');
                return redirect()->route('front.user.profile.personal-info-form');
            }
        } else {
            return redirect()->route('front.user.login');
        }
        return $next($request);
    }
}
