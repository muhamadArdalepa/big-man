<?php

namespace Database\Seeders;

use App\Models\Tim;
use App\Models\User;
use App\Models\Paket;
use App\Models\Pemasangan;
use App\Models\TimAnggota;
use App\Models\JenisGangguan;
use App\Models\JenisPekerjaan;
use App\Models\Pekerjaan;
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
        \App\Models\Wilayah::factory()->count(10)->create();
        
            JenisPekerjaan::create([
                'nama_pekerjaan' => 'Pemasangan',
                'ket' => '-'
            ]);
            JenisPekerjaan::create([
                'nama_pekerjaan' => 'Perbaikan',
                'ket' => '-'
            ]);

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
                    'password' => 'sumeru',
                    'foto_profil' => 'profile/Nilou.webp',
                    'wilayah_id' => 1,
                    'no_telp' => "6281521544674",
                ]
            );
    
            \App\Models\User::factory()->count(300)->create();
            \App\Models\Paket::factory()->count(10)->create();
    
            
            $tim = null;
            foreach (\App\Models\Wilayah::get() as $i => $wilayah) {
                $teknisis = User::where(['wilayah_id' => $wilayah->id, 'role' => 2])->get();
    
                foreach ($teknisis as $i => $teknisi) {
                    if ($i % 3 == 0) {
                        $tim = Tim::create([
                            'user_id' => $teknisi->id,
                            'status' => 'Standby'
                        ]);
    
    
                    }
                    TimAnggota::create([
                        'tim_id' => $tim->id,
                        'user_id' => $teknisi->id,
                    ]);
                };
            }
    
    
    
            $pelanggans = User::where('role', 3)->get();
            foreach ($pelanggans as $pelanggan) {
                $pemasangan = new Pemasangan;
                $pemasangan->pelanggan = $pelanggan->id;
                $pemasangan->nik = fake()->numerify('################');
                $pemasangan->foto_ktp = 'pemasangan/dummy.jpg';
                $pemasangan->foto_rumah = 'pemasangan/dummy.jpg';
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
                $pemasangan->status = 'aktif';
                $pemasangan->save();
                
                $tim = Tim::inRandomOrder()->select('tims.id')->where('wilayah_id',$pelanggan->wilayah_id)->join('users','user_id','=','users.id')->first();
                if (!$tim) {
                    return $pelanggan;
                }
                $pekerjaan = Pekerjaan::create([
                    'tim_id' => $tim->id,
                    'wilayah_id' => $pelanggan->wilayah_id,
                    'jenis_pekerjaan_id' => 1,
                    'pemasangan_id' => $pemasangan->id,
                    'poin' => [10,30,50][random_int(0,2)],
                    'status' => 'selesai',
                ]);
    
                $teknisis = User::where('tim_id',$tim->id)->join('tim_anggotas','users.id','=','user_id')->get();
                foreach ($teknisis as $teknisi) {
                    $teknisi->poin += $pekerjaan->poin;
                    $teknisi->save();
                }
            }
        
    }
}
