<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\Notification;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // If admin or staff, redirect to admin dashboard
        if ($user->isAdmin() || $user->isStaff()) {
            return redirect()->route('admin.dashboard');
        }

        // For students/teachers
        $activeBorrowings = Borrowing::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'pending'])
            ->with('equipment')
            ->get();

        $recentBorrowings = Borrowing::where('user_id', $user->id)
            ->with('equipment')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $availableEquipment = Equipment::where('status', 'available')
            ->where('quantity', '>', 0)
            ->count();

        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('dashboard', compact(
            'activeBorrowings',
            'recentBorrowings',
            'availableEquipment',
            'unreadNotifications'
        ));
    }
}