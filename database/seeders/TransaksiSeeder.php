<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id ID');

        for ($x = 1; $x <= 20; $x++) {

            $tgl_hari_ini = date('Y-m-d');
            $jenis = $faker->randomElement(["Pemasukan", "Pengeluaran"]);
            $kategori = $faker->randomElement(["2","3","4","5"]);
            $nominal = $faker->randomElement([
                "100000",
                "200000",
                "300000",
                "50000",
                "1000000",
                "1500000",
            ]);

            $keterangan = "";

            DB::table('transaksi')->insert([
                'tanggal' => $tgl_hari_ini,
                'jenis' => $jenis,
                'kategori_id' => $kategori,
                'nominal' => $nominal,
                'keterangan' => $keterangan
            ]);
            
    }
}
}
