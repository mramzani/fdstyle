<?php

namespace Modules\User\Services\Auth;


use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait AuthUser
{
    use ThrottlesLogins;

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Response
     */
    public function login(Request $request)
    {

        $this->validateLogin($request);

        if (method_exists($this,'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)){
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)){

            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);

    }



    private function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() =>'required|string|exists:users,mobile',
            'password' => 'required|string',
        ]);
    }

    private function attemptLogin(Request $request): bool
    {
        return $this->guard()->attempt($this->credentials($request),$request->boolean('remember_me'));
    }

    private function username():string
    {
        return 'mobile';
    }

    private function guard()
    {
        return Auth::guard('admin');
    }

    private function credentials(Request $request): array
    {
        return $request->only($this->username(),'password');
    }

    private function sendLoginResponse(Request $request)
    {

        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('dashboard.index');

    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('user::auth.failed')],
        ]);
    }

    /**
     * @return string
     */
    private function redirectPath(): string
    {
        if (method_exists($this,'redirectTo')){
            return $this->redirectTo();
        }
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    public function logout(Request $request)
    {

        $this->guard()->logout();
        //$request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([],204)
            : redirect()->route('auth.login');
    }

}
