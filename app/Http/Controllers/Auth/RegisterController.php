<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/login';

    public function __construct()
    {
        // No middleware here
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:student,officer,professor,dean'],
            'position' => ['nullable', 'string', 'max:255'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'position' => $data['position'] ?? null,
            'status' => 'active',
            'approval_status' => 'pending', // Lahat ng bagong register ay pending muna
        ]);

        return $user;
    }

    // Override ang registered method para magpakita ng message
    protected function registered(Request $request, $user)
    {
        auth()->logout(); // I-logout muna kasi pending pa ang account
        
        return redirect()->route('login')
            ->with('warning', 'Your account has been created but needs admin approval before you can login. Please wait for confirmation.');
    }
}