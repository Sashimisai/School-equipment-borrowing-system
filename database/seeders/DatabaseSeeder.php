<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin Account
        User::create([
            'name' => 'DWCC Admin',
            'email' => 'DWCCADMIN@GMAIL.COM',
            'password' => Hash::make('DWCC123'),
            'role' => 'admin',
            'position' => 'System Administrator',
            'status' => 'active',
            'approval_status' => 'approved',
        ]);

        // Create sample pending users for testing
        User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@student.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'position' => null,
            'status' => 'active',
            'approval_status' => 'pending',
        ]);

        User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@officer.com',
            'password' => Hash::make('password123'),
            'role' => 'officer',
            'position' => 'Student Council President',
            'status' => 'active',
            'approval_status' => 'pending',
        ]);

        // Create Equipment
        $equipment = [
            [
                'name' => 'Epson Projector X500',
                'category' => 'projector',
                'serial_number' => 'PRJ-001',
                'description' => 'High definition projector for classroom use',
                'status' => 'available',
                'quantity' => 3,
            ],
            [
                'name' => 'Dell Latitude Laptop',
                'category' => 'laptop',
                'serial_number' => 'LPT-001',
                'description' => '15.6 inch laptop for presentations',
                'status' => 'available',
                'quantity' => 5,
            ],
            [
                'name' => 'Sony Wireless Microphone',
                'category' => 'microphone',
                'serial_number' => 'MIC-001',
                'description' => 'Wireless microphone system for events',
                'status' => 'available',
                'quantity' => 4,
            ],
            [
                'name' => 'Canon DSLR Camera',
                'category' => 'camera',
                'serial_number' => 'CAM-001',
                'description' => 'Digital camera for documentation',
                'status' => 'available',
                'quantity' => 2,
            ],
            [
                'name' => 'Microscope Lab Kit',
                'category' => 'laboratory',
                'serial_number' => 'LAB-001',
                'description' => 'Complete microscope kit for biology lab',
                'status' => 'available',
                'quantity' => 10,
            ],
        ];

        foreach ($equipment as $item) {
            Equipment::create($item);
        }
    }
}