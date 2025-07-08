<?php

namespace Database\Seeders;

use App\Models\Peran;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Admin',
            'peran_id' => Peran::PERAN_ADMIN_ID,
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        // User::create([
        //     'nama' => 'Staf',
        //     'peran_id' => Peran::PERAN_STAF_ID,
        //     'username' => 'staf',
        //     'password' => Hash::make('admin123'),
        // ]);
        User::create([
            'nama' => 'Kades',
            'peran_id' => Peran::PERAN_KADES_ID,
            'username' => 'kades',
            'password' => Hash::make('kades123'),
        ]);
    }
}