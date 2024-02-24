<?php

namespace Database\Seeders;

use App\Models\Pengaduan;
use App\Models\User;
use App\Models\Tujuan;
use Illuminate\Database\Seeder;
// use App\Models\Tanggapan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {

        Tujuan::create([
            'name' => 'Pelayanan Pendaftaran Penduduk'
        ]);
        Tujuan::create([
            'name' => 'Pelayanan Pencatatan Sipil'
        ]);
        Tujuan::create([
            'name' => 'Pengelolaan Informasi Administrasi Kependudukan'
        ]);
        Tujuan::create([
            'name' => 'Pemanfaatan Data Dan Inovasi Pelayanan'
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
        ]);
    }
}
