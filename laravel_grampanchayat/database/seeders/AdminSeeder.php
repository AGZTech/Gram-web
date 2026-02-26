<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'प्रशासक',
            'email' => 'admin@grampanchayat.gov.in',
            'password' => Hash::make('Admin@123'),
            'phone' => '9876543210',
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        Admin::create([
            'name' => 'संपादक',
            'email' => 'editor@grampanchayat.gov.in',
            'password' => Hash::make('Editor@123'),
            'phone' => '9876543211',
            'role' => 'editor',
            'is_active' => true,
        ]);
    }
}
