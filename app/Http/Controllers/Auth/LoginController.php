<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        // No middleware here
    }

    // Override ang login method para i-check kung approved ang account
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check kung may user na may ganitong email
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && !$user->isAdmin() && $user->approval_status === 'pending') {
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->with('error', 'Your account is pending approval. Please wait for admin confirmation.');
        }

        if ($user && !$user->isAdmin() && $user->approval_status === 'rejected') {
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->with('error', 'Your account has been rejected. Reason: ' . ($user->admin_remarks ?? 'No reason provided'));
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    // I-redirect ang admin sa admin dashboard
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('dashboard');
    }
}