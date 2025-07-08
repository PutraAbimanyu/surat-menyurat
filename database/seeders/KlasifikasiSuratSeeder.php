<?php

namespace Database\Seeders;

use App\Models\KlasifikasiSurat;
use Illuminate\Database\Seeder;

class KlasifikasiSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daftarKlasifikasiSurat = [
            'Organisasi',
            'Kepegawaian',
            'Keuangan',
            'Pendidikan',
        ];

        foreach ($daftarKlasifikasiSurat as $klasifikasiSurat) {
            KlasifikasiSurat::create([
                'nama_klasifikasi' => $klasifikasiSurat,
            ]);
        }
    }
}
