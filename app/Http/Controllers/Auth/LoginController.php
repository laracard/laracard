<?php

namespace App\Http\Controllers\Auth;

use App\Events\LoginEvent;
use App\Http\Controllers\Controller;
use App\Interfaces\Auth\AuthInterface;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    private $maxAttempts;
    private $decayMinutes;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $authService;

    use ApiResponse;

    /**
     * LoginController constructor.
     * @param  AuthInterface  $authService
     */
    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
//        $this->middleware('guest')->except('logout');
        $this->maxAttempts = 10;
        $this->decayMinutes = 1;
    }

    public function login(Request $request)
    {
//        $this->validateLogin($request);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        $user = User::query()->where('email', $request['email'])->where('status', 1)->firstOrFail();
        if ($this->authService->check($user, $request['password'])) {
            event(new LoginEvent($user));
            return $this->sendLoginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->success();
    }

    /**
     * [description]
     * @param  Request  $request
     * @throws \Illuminate\Validation\ValidationException
     * @author: cuibo 2019/10/8 15:27
     */
    public function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string|min:8',
        ]);
    }

}
