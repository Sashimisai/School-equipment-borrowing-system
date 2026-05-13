<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'totalEquipment' => Equipment::count(),
            'availableEquipment' => Equipment::where('status', 'available')->count(),
            'borrowedEquipment' => Equipment::where('status', 'borrowed')->count(),
            'maintenanceEquipment' => Equipment::where('status', 'maintenance')->count(),
            'pendingRequests' => Borrowing::where('status', 'pending')->count(),
            'approvedRequests' => Borrowing::where('status', 'approved')->count(),
            'rejectedRequests' => Borrowing::where('status', 'rejected')->count(),
            'returnedRequests' => Borrowing::where('status', 'returned')->count(),
            'totalUsers' => User::count(),
            'pendingUsers' => User::where('approval_status', 'pending')->where('role', '!=', 'admin')->count(),
            'totalStudents' => User::where('role', 'student')->count(),
            'totalStaff' => User::where('role', 'staff')->count(),
        ];

        $recentBorrowings = Borrowing::with(['user', 'equipment'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $pendingRequests = Borrowing::with(['user', 'equipment'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBorrowings', 'pendingRequests'));
    }

    /**
     * View all borrow requests with filters
     */
    public function requests(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('borrow_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('borrow_date', '<=', $request->date_to);
        }

        // Search by user or equipment
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('equipment', function($equipQuery) use ($search) {
                    $equipQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(15);
        $borrowings->appends($request->all());

        return view('admin.requests', compact('borrowings'));
    }

    /**
     * Approve, Reject, or Mark as Returned a borrow request
     */
    public function approveRequest(Request $request, Borrowing $borrowing)
    {
        // Validate
        $validated = $request->validate([
            'action' => 'required|in:approved,rejected,returned',
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        $action = $validated['action'];
        $adminRemarks = $validated['admin_remarks'] ?? '';

        // Update borrowing record
        $borrowing->status = $action;
        $borrowing->admin_remarks = $adminRemarks;

        // Handle each action
        if ($action === 'approved') {
            $borrowing->approved_at = now();
            Equipment::where('id', $borrowing->equipment_id)->update(['status' => 'borrowed']);
        }

        if ($action === 'returned') {
            $borrowing->returned_at = now();
            Equipment::where('id', $borrowing->equipment_id)->update(['status' => 'available']);
        }

        if ($action === 'rejected') {
            Equipment::where('id', $borrowing->equipment_id)->update(['status' => 'available']);
        }

        $borrowing->save();

        // Notification messages
        $messages = [
            'approved' => "Your borrow request for \"{$borrowing->equipment->name}\" has been approved.",
            'rejected' => "Your borrow request for \"{$borrowing->equipment->name}\" has been rejected.",
            'returned' => "Your borrowed equipment \"{$borrowing->equipment->name}\" has been marked as returned.",
        ];

        $notificationMessage = $messages[$action];
        if ($adminRemarks) {
            $notificationMessage .= " Remarks: {$adminRemarks}";
        }

        // Create notification
        Notification::create([
            'user_id' => $borrowing->user_id,
            'type' => 'borrow_' . $action,
            'message' => $notificationMessage,
        ]);

        // Success messages
        $successMessages = [
            'approved' => '✅ Borrow request has been approved successfully!',
            'rejected' => '❌ Borrow request has been rejected.',
            'returned' => '✅ Equipment has been returned successfully!',
        ];

        return redirect()->route('admin.requests')->with('success', $successMessages[$action]);
    }

    /**
     * User Management Page
     */
    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $query->orderBy('approval_status')
              ->orderBy('created_at', 'desc');

        $users = $query->paginate(15);
        $users->appends($request->all());

        $pendingCount = User::where('approval_status', 'pending')
            ->where('role', '!=', 'admin')
            ->count();

        return view('admin.users', compact('users', 'pendingCount'));
    }

    /**
     * Approve or Reject User Account
     */
    public function approveUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'approval_status' => 'required|in:approved,rejected',
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        $status = $validated['approval_status'];
        $remarks = $validated['admin_remarks'] ?? '';

        $user->update([
            'approval_status' => $status,
            'admin_remarks' => $remarks,
        ]);

        // Notification message
        if ($status === 'approved') {
            $message = "✅ Your account has been approved! You can now login and use the system.";
        } else {
            $message = "❌ Your account has been rejected.";
            if ($remarks) {
                $message .= " Reason: {$remarks}";
            }
        }

        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_' . $status,
            'message' => $message,
        ]);

        $action = $status === 'approved' ? 'approved' : 'rejected';
        return redirect()->route('admin.users')->with('success', "User {$user->name} has been {$action}!");
    }

    /**
     * Update User Role and Position
     */
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:student,officer,professor,dean,staff',
            'position' => 'nullable|string|max:255',
        ]);

        $user->update([
            'role' => $validated['role'],
            'position' => $validated['position'] ?? null,
        ]);

        return redirect()->route('admin.users')->with('success', "{$user->name}'s role has been updated successfully!");
    }

    /**
     * Delete User Account
     */
    public function deleteUser(User $user)
    {
        // Cannot delete admin
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin accounts!');
        }

        $userName = $user->name;

        // Delete related records
        $user->borrowings()->delete();
        $user->notifications()->delete();
        
        // Delete user
        $user->delete();

        return redirect()->route('admin.users')->with('success', "User {$userName} has been permanently deleted!");
    }
}