<?php

namespace Database\Seeders;

use App\Models\Tim;
use App\Models\User;
use App\Models\Paket;
use App\Models\Pemasangan;
use App\Models\TimAnggota;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\JenisGangguan::factory()->count(5)->create();
        \App\Models\Wilayah::factory()->count(10)->create();

        User::create([
            'nama' => 'Admin',
            'speciality' => 'Admin Ganteng',
            'role' => 1,
            'email' => 'admin@big.com',
            'password' => '123',
            'foto_profil' => 'kamisato.webp',
            'wilayah_id' => 1,
            'no_telp' => "6281521544674",
        ]);
        User::create([
            'nama' => 'Nilou',
            'speciality' => 'Admin Ganteng',
            'role' => 2,
            'email' => 'niloucantik@big.com',
            'password' => 'sumeru',
            'foto_profil' => 'Nilou.webp',
            'wilayah_id' => 1,
            'no_telp' => "6281521544674",
        ]);
        \App\Models\User::factory()->count(100)->create();
        \App\Models\Paket::factory()->count(10)->create();

        $pelanggans = User::select('id')->where('role', 3)->get();
        foreach ($pelanggans as $pelanggan) {
            $pemasangan = new Pemasangan;
            $pemasangan->pelanggan = $pelanggan->id;
            $pemasangan->marketer = \App\Models\User::inRandomOrder()->where('role',2)->first()->id;
            $pemasangan->nik = fake()->numerify('################');
            $pemasangan->foto_ktp = 'dummy.jpg';
            $pemasangan->foto_rumah = 'dummy.jpg';
            $pemasangan->alamat = fake()->address();
            $pemasangan->koordinat_rumah = fake()->longitude() . ', ' . fake()->latitude();
            $pemasangan->koordinat_odp = fake()->longitude() . ', ' . fake()->latitude();
            $pemasangan->serial_number = fake()->regexify('[A-Z]{3}\d{3}[A-Z]{3}\d{3}');
            $pemasangan->ssid = fake()->word();
            $pemasangan->password = fake()->password();
            $pemasangan->paket_id = Paket::inRandomOrder()->first()->id;
            $pemasangan->hasil_opm_user = '24';
            $pemasangan->hasil_opm_odp = '24';
            $pemasangan->kabel_terpakai = fake()->numberBetween(10, 1000);
            $pemasangan->port_odp = fake()->numberBetween(1, 24);
            $pemasangan->status = 1;
            $pemasangan->save();
        }

    }
}
