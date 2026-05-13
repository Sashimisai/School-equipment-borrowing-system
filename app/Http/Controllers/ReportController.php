<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Equipment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment']);

        // Date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->get();
        $categories = Equipment::distinct()->pluck('category');

        // Calculate statistics
        $stats = [
            'total' => $borrowings->count(),
            'approved' => $borrowings->where('status', 'approved')->count(),
            'pending' => $borrowings->where('status', 'pending')->count(),
            'rejected' => $borrowings->where('status', 'rejected')->count(),
            'returned' => $borrowings->where('status', 'returned')->count(),
        ];

        return view('admin.reports', compact('borrowings', 'categories', 'stats'));
    }

    public function print(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment']);

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('category') && $request->category) {
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->get();

        return view('admin.reports-print', compact('borrowings'));
    }
}