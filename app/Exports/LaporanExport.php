<?php

namespace App\Exports;

use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanExport implements FromView
{
    public function view(): View
    {
        $kategori = Kategori::all();

        $dari = $_GET['dari'];
        $sampai = $_GET['sampai'];
        $id_kategori = $_GET['kategori'];

        if ($id_kategori == "semua") {
            $laporan = Transaksi::whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        } else {
            $laporan = Transaksi::where('kategori_id', $id_kategori)
                ->whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        }

        return view('laporan_excel', [
            'laporan' => $laporan,
            'kategori' => $kategori,
            'dari' => $dari,
            'sampai' => $sampai,
            'kat' => $id_kategori
        ]);
    }
}