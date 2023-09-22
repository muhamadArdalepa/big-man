<?php

namespace Database\Seeders;

use App\Models\Absen;
use App\Models\Aktivitas;
use App\Models\Tim;
use App\Models\User;
use App\Models\Paket;
use App\Models\Pemasangan;
use App\Models\TimAnggota;
use App\Models\JenisGangguan;
use App\Models\JenisPekerjaan;
use App\Models\Kesulitan;
use App\Models\Pekerjaan;
use App\Models\Wilayah;
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
        // \App\Models\Wilayah::factory()->count(10)->create();
        $wilayah = [
            'Pontianak',
            'Sintang',
            'Ngabang'
        ];

        foreach ($wilayah as $key => $value) {
            Wilayah::create([
                'nama_wilayah' => $value,
                'ket' => '-'
            ]);
        }


        JenisGangguan::create([
            'nama_gangguan' => 'Koneksi Lambat',
            'ket' => 'Masalah yang dihadapi adalah koneksi internet yang sangat lambat, sehingga aktivitas online seperti streaming video, browsing, atau mengunduh file menjadi sulit untuk dilakukan.'
        ]);
        JenisGangguan::create([
            'nama_gangguan' => 'Putus-Putus',
            'ket' => 'Koneksi internet sering mengalami gangguan dengan terputus-putus, yang mengakibatkan gangguan dalam berbagai aktivitas online.'
        ]);
        JenisGangguan::create([
            'nama_gangguan' => 'Tidak Bisa Terhubung ke Jaringan',
            'ket' => 'Perangkat tidak dapat dihubungkan ke jaringan internet sama sekali, meskipun sudah mencoba menghubungkan perangkat ke jaringan Wi-Fi atau menggunakan koneksi kabel.'
        ]);
        JenisGangguan::create([
            'nama_gangguan' => 'Ping Tinggi',
            'ket' => 'Keterlambatan dalam respon jaringan (tinggi ping) terjadi, sehingga permainan online atau aplikasi yang membutuhkan latensi rendah menjadi sulit dimainkan atau digunakan.'
        ]);

        User::create(
            [
                'nama' => 'Admin',
                'speciality' => 'Admin Ganteng',
                'role' => 1,
                'email' => 'admin@big.com',
                'email_verified_at' => now(),
                'password' => '123',
                'foto_profil' => 'profile/kamisato.webp',
                'wilayah_id' => 1,
                'no_telp' => "6281521544674",
            ]
        );
        User::create(
            [
                'nama' => 'Nilou',
                'speciality' => 'Admin Ganteng',
                'role' => 2,
                'email' => 'niloucantik@big.com',
                'email_verified_at' => now(),
                'password' => 'sumeru',
                'foto_profil' => 'profile/Nilou.webp',
                'wilayah_id' => 1,
                'no_telp' => "6281521544674",
            ]
        );

        $kecepatan = ['10', '30', '50', '100', '200'];
        foreach ($kecepatan as $i => $kc) {
            \App\Models\Paket::create([
                'nama_paket' => 'Paket Dasar ' . $i + 1,
                'kecepatan' => $kc,
                'harga' => $kc * 1000 / 4,
                'ket' => fake()->sentence(),
            ]);
        }
        \App\Models\User::factory()->count(50)->create();


        $tim = null;
        // $absenId = 0;
        foreach (\App\Models\Wilayah::get() as $wilayah) {

            $teknisis = User::where(['wilayah_id' => $wilayah->id, 'role' => 2])->get();
            foreach ($teknisis as $i => $teknisi) {
                //     for ($j = 1; $j <= now()->month; $j++) {
                //         for ($k = 1; $k <= cal_days_in_month(CAL_GREGORIAN, $j, 2023); $k++) {
                //             Absen::insert([
                //                 'id' => ++$absenId,
                //                 'user_id' => $teknisi->id,
                //                 'created_at' => '2023-' . $j . '-' . $k . ' 08:00:00',
                //                 'status' => 1,
                //             ]);
                //             $time = [8, 11, 13, 16];
                //             foreach ($time as $t) {
                //                 Aktivitas::insert([
                //                     'user_id' => $teknisi->id,
                //                     'absen_id' => $absenId,
                //                     'foto' => 'aktivitas/dummy.jpg',
                //                     'aktivitas' => fake()->sentence(),
                //                     'alamat' => 'Gg. Fitrah, Bangka Belitung Laut, Pontianak, West Kalimantan',
                //                     'koordinat' => '-0.0779319,109.3680154',
                //                     'created_at' => '2023-' . $j . '-' . $k . ' ' . $t . ':00:00'
                //                 ]);
                //             }
                //         }
                //     }

                if ($i % 3 == 0) {
                    $tim = Tim::create([
                        'user_id' => $teknisi->id,
                        'status' => 1
                    ]);
                }
                TimAnggota::create([
                    'tim_id' => $tim->id,
                    'user_id' => $teknisi->id,
                ]);
            };
        }
        Kesulitan::create([
            'kesulitan' => 'Mudah',
            'poin' => 10,
        ]);
        Kesulitan::create([
            'kesulitan' => 'Sedang',
            'poin' => 20,
        ]);
        Kesulitan::create([
            'kesulitan' => 'Sulit',
            'poin' => 30,
        ]);
        Kesulitan::create([
            'kesulitan' => 'Sangat sulit',
            'poin' => 40,
        ]);


        // $pelanggans = User::where('role', 3)->get();
        // foreach ($pelanggans as $i => $pelanggan) {
        //     if ($i > round($pelanggans->count() / 3)) {

        //         Pemasangan::insert([
        //             'pelanggan_id' => $pelanggan->id,
        //             'nik' => fake()->numerify('610##############'),
        //             'foto_ktp' => 'pemasangan/foto_ktp.jpg',
        //             'foto_rumah' => 'pemasangan/foto_rumah.jpg',
        //             'foto_letak_modem' => 'pemasangan/foto_letak_modem.jpg',
        //             'foto_opm_odp' => 'pemasangan/foto_opm_odp.jpg',
        //             'foto_opm_user' => 'pemasangan/foto_opm_user.jpg',
        //             'foto_modem' => 'pemasangan/foto_modem.jpg',
        //             'alamat' => fake()->address(),
        //             'koordinat_rumah' => fake()->longitude() . ',' . fake()->latitude(),
        //             'koordinat_odp' => fake()->longitude() . ',' . fake()->latitude(),
        //             'serial_number' => 'ZTE' . fake()->regexify('[A-Z]{3}\d{3}[A-Z]{3}\d{3}'),
        //             'ssid' => fake()->word(),
        //             'password' => fake()->password(),
        //             'paket_id' => Paket::inRandomOrder()->first()->id,
        //             'hasil_opm_user' => '24',
        //             'hasil_opm_odp' => '24',
        //             'kabel_terpakai' => fake()->numberBetween(10, 1000),
        //             'port_odp' => fake()->numberBetween(1, 24),
        //             'status' => 4,
        //             'created_at' => fake()->dateTimeThisMonth(),
        //         ]);
        //         $pemasangan = Pemasangan::find($i);
        //         $tim = Tim::inRandomOrder()->select('tims.id')->where('wilayah_id', $pelanggan->wilayah_id)->join('users', 'user_id', '=', 'users.id')->first();
        //         if (!$tim) {
        //             return $pelanggan;
        //         }
        //         $pekerjaan = Pekerjaan::create([
        //             'tim_id' => $tim->id,
        //             'pemasangan_id' => $pemasangan->id,
        //             'poin' => [10, 30, 50][random_int(0, 2)],
        //             'created_at' => $pemasangan->created_at
        //         ]);

        //         $teknisis = User::where('tim_id', $tim->id)->join('tim_anggotas', 'users.id', '=', 'user_id')->get();
        //         foreach ($teknisis as $teknisi) {
        //             $teknisi->poin += $pekerjaan->poin;
        //             $teknisi->save();
        //         }
        //     } else if ($i > round($pelanggans->count() * 2 / 3)) {
        //         $pemasangan = new Pemasangan;
        //         $pemasangan->pelanggan_id = $pelanggan->id;
        //         $pemasangan->nik = fake()->numerify('610##############');
        //         $pemasangan->foto_ktp = 'pemasangan/dummy.jpg';
        //         $pemasangan->foto_rumah = 'pemasangan/dummy.jpg';
        //         $pemasangan->alamat = fake()->address();
        //         $pemasangan->koordinat_rumah = fake()->longitude() . ',' . fake()->latitude();
        //         $pemasangan->koordinat_odp = fake()->longitude() . ',' . fake()->latitude();
        //         $pemasangan->paket_id = Paket::inRandomOrder()->first()->id;
        //         $pemasangan->status = 2;
        //         $pemasangan->save();

        //         $tim = Tim::inRandomOrder()->select('tims.id')->where('wilayah_id', $pelanggan->wilayah_id)->join('users', 'user_id', '=', 'users.id')->first();
        //         if (!$tim) {
        //             return $pelanggan;
        //         }
        //         $pekerjaan = Pekerjaan::create([
        //             'tim_id' => $tim->id,
        //             'pemasangan_id' => $pemasangan->id,
        //             'poin' => [10, 30, 50][random_int(0, 2)],
        //             'created_at' => $pemasangan->created_at
        //         ]);
        //         for ($j = 0; $j < random_int(3, 10); $j++) {
        //             Aktivitas::create([
        //                 'user_id' => 1,
        //                 'pekerjaan_id' => $pekerjaan->id,
        //                 'foto' => 'aktivitas/dummy' . random_int(1, 5) . '.png',
        //                 'koordinat' => $pemasangan->koordinat_rumah,
        //                 'alamat' => $pemasangan->alamat,
        //                 'aktivitas' => fake()->sentence()
        //             ]);
        //         }
        //     } else {
        //         $pemasangan = new Pemasangan;
        //         $pemasangan->pelanggan_id = $pelanggan->id;
        //         $pemasangan->nik = fake()->numerify('610##############');
        //         $pemasangan->foto_ktp = 'pemasangan/dummy.jpg';
        //         $pemasangan->foto_rumah = 'pemasangan/dummy.jpg';
        //         $pemasangan->alamat = fake()->address();
        //         $pemasangan->koordinat_rumah = fake()->longitude() . ',' . fake()->latitude();
        //         $pemasangan->paket_id = Paket::inRandomOrder()->first()->id;
        //         $pemasangan->status = 1;
        //         $pemasangan->save();
        //     }
        // }
    }
}
