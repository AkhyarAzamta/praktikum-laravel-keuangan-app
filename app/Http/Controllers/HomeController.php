<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function kategori()
    {
        $kategori = Kategori::all();
        return view('kategori', ['kategori' => $kategori]);
    }

    public function kategori_tambah()
    {
        return view('kategori_tambah');
    }

    public function kategori_aksi(Request $request)
    {
        $request->validate([
            'kategori' => 'required'
        ]);

        $kategori = $request->kategori;
        Kategori::insert([
            'kategori' => $kategori
        ]);

        return redirect('kategori')->with('success', 'Kategori Berhasil Disimpan');
    }

    public function kategori_edit($id)
    {
        $kategori = Kategori::find($id);
        return view('kategori_edit', ['kategori' => $kategori]);
    }

    public function kategori_update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required'
        ]);

        $nama_kategori = $request->kategori;

        $kategori = Kategori::find($id);
        $kategori->kategori = $nama_kategori;
        $kategori->save();

        return redirect('kategori')->with('success', 'Kategori Berhasil Disimpan');
    }

    public function kategori_hapus($id)
    {
        $kategori = Kategori::find($id);
        $kategori->delete();

        $transaksi = Transaksi::where('kategori_id', $id);
        $transaksi->delete();

        return redirect('kategori')->with('success', 'Kategori Berhasil Dihapus');
    }

    public function transaksi()
    {
        // mengambil data transaksi dengan paginasi
        $transaksi = Transaksi::paginate(10); // Gantilah 10 dengan jumlah item per halaman yang diinginkan
        // passing data transaksi ke view transaksi.blade.php
        return view('transaksi', ['transaksi' => $transaksi]);
    }

    public function transaksi_tambah()
    {
        $kategori = Kategori::all();

        return view('transaksi_tambah', ['kategori' => $kategori]);

    }

    public function transaksi_aksi(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'jenis' => 'required|string',
        'kategori' => 'required|integer',
        'nominal' => 'required|integer',
        'keterangan' => 'required|string'
    ]);

    Transaksi::create([
        'tanggal' => $request->tanggal,
        'jenis' => $request->jenis,
        'kategori_id' => $request->kategori,
        'nominal' => $request->nominal,
        'keterangan' => $request->keterangan
    ]);

    return redirect('transaksi')->with('success', 'Transaksi Berhasil Disimpan');
    }
    public function transaksi_edit($id)
    {
        $kategori = Kategori::all();
        $transaksi = Transaksi::find($id);

        return view('transaksi_edit', ['kategori' => $kategori, 'transaksi' => $transaksi]);
    }

    public function transaksi_update(Request $request, $id)
{
    $request->validate([
        'tanggal' => 'required|date',
        'jenis' => 'required|string',
        'kategori' => 'required|integer',
        'nominal' => 'required|integer',
        'keterangan' => 'required|string'
    ]);

    $transaksi = Transaksi::findOrFail($id);

    $transaksi->tanggal = $request->tanggal;
    $transaksi->jenis = $request->jenis;
    $transaksi->kategori_id = $request->kategori;
    $transaksi->nominal = $request->nominal; // Pastikan nominal adalah integer
    $transaksi->keterangan = $request->keterangan;

    $transaksi->save();

    return redirect('transaksi')->with('success', 'Transaksi Berhasil Di Update');
    }

    public function transaksi_hapus($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->delete();

        return redirect('transaksi')->with('success', 'Transaksi Berhasil Dihapus');
    }

    public function transaksi_cari(Request $request)
    {
        $cari = $request->cari;

        $transaksi = Transaksi::where(function ($query) use ($cari) {
            $query->where('jenis', 'like', "%". $cari . "%")
            ->orWhere("tanggal", "like", "%". $cari . "%")
            ->orWhere("keterangan", "like", "%". $cari . "%")
            ->orWhere("nominal", "=", $cari);
        })->orderBy('id', 'desc')->paginate(10);

        return view('transaksi', ['transaksi' => $transaksi]);
    }

    public function laporan()
    {
        $kategori = Kategori::all();

        return view('laporan', ['kategori'=> $kategori]);
    }

    public function laporan_hasil(Request $request)
    {
        $request->validate([
            'dari' => 'required',
            'sampai' => 'required'
        ]);

        $kategori = Kategori::all();

        $dari = $request->dari;
        $sampai = $request->sampai;
        $id_kategori = $request->kategori;

        if($id_kategori == "semua") {
            $laporan = Transaksi::whereDate('tanggal', '>=', $dari)
            ->whereDate('tanggal', '<=', $sampai)
            ->orderBy('id', 'desc')->get();
    } else {
        $laporan = Transaksi::whereDate('kategori_id', $id_kategori)
        ->whereDate('tanggal', '>=', $dari)
        ->whereDate('tanggal', '<=', $dari)
        ->orderBy('id','desc')->get();
    }

    return view('laporan_hasil', 
    ['laporan' => $laporan,
    'dari' => $dari,
    'sampai' => $sampai, 
    'kategori' => $kategori
]);

}
}