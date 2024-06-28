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

    Transaksi::insert([
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

    $transaksi = Transaksi::find($id);

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

}
