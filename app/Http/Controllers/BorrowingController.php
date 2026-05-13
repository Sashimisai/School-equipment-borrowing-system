<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\Notification;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $query = Borrowing::where('user_id', auth()->id())
            ->with('equipment');

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        // Check if user has overdue borrowings
        $hasOverdue = Borrowing::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->where('return_date', '<', now())
            ->exists();

        $availableEquipment = Equipment::where('status', 'available')
            ->where('quantity', '>', 0)
            ->get();

        return view('borrowings.create', compact('availableEquipment', 'hasOverdue'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:borrow_date',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Check if equipment is still available
        $equipment = Equipment::find($validated['equipment_id']);
        if ($equipment->status !== 'available' || $equipment->quantity < 1) {
            return redirect()->back()
                ->with('error', 'Equipment is no longer available for borrowing.');
        }

        // Check for overlapping borrowings
        $overlapping = Borrowing::where('equipment_id', $validated['equipment_id'])
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'returned')
            ->where(function($query) use ($validated) {
                $query->whereBetween('borrow_date', [$validated['borrow_date'], $validated['return_date']])
                    ->orWhereBetween('return_date', [$validated['borrow_date'], $validated['return_date']]);
            })
            ->exists();

        if ($overlapping) {
            return redirect()->back()
                ->with('error', 'You already have a borrowing request for this period.');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Borrowing::create($validated);

        return redirect()->route('borrowings.index')
            ->with('success', 'Borrow request submitted successfully! Waiting for approval.');
    }

    public function show(Borrowing $borrowing)
    {
        // Ensure user can only view their own borrowings
        if ($borrowing->user_id !== auth()->id() && !auth()->user()->isAdmin() && !auth()->user()->isStaff()) {
            return redirect()->route('borrowings.index')
                ->with('error', 'Unauthorized access.');
        }

        $borrowing->load(['user', 'equipment']);
        return view('borrowings.show', compact('borrowing'));
    }
}