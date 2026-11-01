<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akun = [
            ['nama'=> 'Bu Dini', 'email'=> 'koordinator@mbg.test', 'peran'=> 'koordinator'],
            ['nama'=> 'Pak Nanang', 'email'=> 'pemantau@mbg.test', 'peran'=> 'pemantau'],
            ['nama'=> 'Bu Juwita', 'email'=> 'pelapor1@mbg.test', 'peran'=> 'pelapor'],
            ['nama'=> 'Pak Rais', 'email'=> 'pelapor2@mbg.test', 'peran'=> 'pelapor'],
        ];
        foreach($akun as $a) {
            User::create([
                'nama'=> $a['nama'],
                'email'=> $a['email'],
                'password'=> Hash::make('password'),
                'peran'=> $a['peran'],
            ]);
        }
    }
}
