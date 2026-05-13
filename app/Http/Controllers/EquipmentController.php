<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(Request $request)
    {
        $query = Equipment::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $equipment = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Equipment::distinct()->pluck('category');

        return view('equipment.index', compact('equipment', 'categories'));
    }

    public function create()
    {
        // Only admin and staff can create
        if (!auth()->user()->isAdmin() && !auth()->user()->isStaff()) {
            return redirect()->route('equipment.index')
                ->with('error', 'You are not authorized to add equipment.');
        }

        return view('equipment.create');
    }

    public function store(Request $request)
    {
        // Only admin and staff can store
        if (!auth()->user()->isAdmin() && !auth()->user()->isStaff()) {
            return redirect()->route('equipment.index')
                ->with('error', 'You are not authorized to add equipment.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'serial_number' => 'required|string|unique:equipment,serial_number',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('equipment', 'public');
            $validated['image'] = $imagePath;
        }

        Equipment::create($validated);

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment added successfully!');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('borrowings.user');
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        // Only admin and staff can edit
        if (!auth()->user()->isAdmin() && !auth()->user()->isStaff()) {
            return redirect()->route('equipment.index')
                ->with('error', 'You are not authorized to edit equipment.');
        }

        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        // Only admin and staff can update
        if (!auth()->user()->isAdmin() && !auth()->user()->isStaff()) {
            return redirect()->route('equipment.index')
                ->with('error', 'You are not authorized to update equipment.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'serial_number' => 'required|string|unique:equipment,serial_number,' . $equipment->id,
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($equipment->image) {
                \Storage::disk('public')->delete($equipment->image);
            }
            $imagePath = $request->file('image')->store('equipment', 'public');
            $validated['image'] = $imagePath;
        }

        $equipment->update($validated);

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment updated successfully!');
    }

    public function destroy(Equipment $equipment)
    {
        // Only admin can delete
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('equipment.index')
                ->with('error', 'You are not authorized to delete equipment.');
        }

        // Delete image
        if ($equipment->image) {
            \Storage::disk('public')->delete($equipment->image);
        }

        $equipment->delete();

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment deleted successfully!');
    }
}