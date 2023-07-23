<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kotas')->insert([
            'kota' => 'Pontianak'
        ]);
        DB::table('kotas')->insert([
            'kota' => 'Sintang'
        ]);
        DB::table('kotas')->insert([
            'kota' => 'Ngabang'
        ]);
        DB::table('kotas')->insert([
            'kota' => 'Bogor'
        ]);
        DB::table('users')->insert([
            'nama' => 'Admin',
            'role' => 1,
            'email' => 'admin@big.com',
            'password' => bcrypt('123'),
            'kota_id' => 1,
            'no_telp' => "081521544674",
        ]);
        \App\Models\User::factory()->count(50)->create();
        \App\Models\Tim::factory()->count(10)->create();
        \App\Models\TimAnggota::factory()->count(25)->create();
        \App\Models\JenisGangguan::factory()->count(5)->create();
        \App\Models\Laporan::factory()->count(30)->create();
        $pelanggan = \App\Models\User::where('role',3)->get();
        foreach ($pelanggan as $key => $value) {
            DB::table('pemasangans')->insert([
                'user_id' => $value->id,
                'alamat' => fake()->address()
            ]);
        }
    }
}
