<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

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
        $tanggal_hari_ini = date('Y-m-d');
        $bulan_ini = date('m');
        $tahun_ini = date('Y');
        $pemasukan_hari_ini = Transaksi::where('jenis', 'Pemasukan')
        ->whereDate('tanggal', $tanggal_hari_ini)
        ->sum('nominal');

        $pemasukan_bulan_ini = Transaksi::where('jenis', 'Pemasukan')
        ->whereMonth('tanggal', $bulan_ini)
        ->sum('nominal');

        $pemasukan_tahun_ini = Transaksi::where('jenis', 'Pemasukan')
        ->whereYear('tanggal', $tahun_ini)
        ->sum('nominal');

        $seluruh_pemasukan = Transaksi::where('jenis', 'Pemasukan')
        ->sum('nominal');

        $pengluaran_hari_ini = Transaksi::where('jenis', 'Pengeluaran')
        ->whereDate('tanggal', $tanggal_hari_ini)
        ->sum('nominal');

        $pengluaran_bulan_ini = Transaksi::where('jenis', 'Pengeluaran')
        ->whereMonth('tanggal', $bulan_ini)
        ->sum('nominal');

        $pengluaran_tahun_ini = Transaksi::where('jenis', 'Pengeluaran')
        ->whereYear('tanggal', $tahun_ini)
        ->sum('nominal');

        $seluruh_pengeluaran = Transaksi::where('jenis', 'Pengeluaran')
        ->sum('nominal');

        return view('home', [
            'pemasukan_hari_ini' => $pemasukan_hari_ini,
            'pemasukan_bulan_ini' => $pemasukan_bulan_ini,
            'pemasukan_tahun_ini' => $pemasukan_tahun_ini,
            'seluruh_pemasukan' => $seluruh_pemasukan,
            'pengluaran_hari_ini' => $pengluaran_hari_ini,
            'pengluaran_bulan_ini' => $pengluaran_bulan_ini,
            'pengluaran_tahun_ini' => $pengluaran_tahun_ini,
            'seluruh_pengeluaran' => $seluruh_pengeluaran
        ]);
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
            $query->where('jenis', 'like', "%" . $cari . "%")
                ->orWhere("tanggal", "like", "%" . $cari . "%")
                ->orWhere("keterangan", "like", "%" . $cari . "%")
                ->orWhere("nominal", "=", $cari);
        })->orderBy('id', 'desc')->paginate(10);

        return view('transaksi', ['transaksi' => $transaksi]);
    }

    public function laporan()
    {
        $kategori = Kategori::all();

        return view('laporan', ['kategori' => $kategori]);
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

        if ($id_kategori == "semua") {
            $laporan = Transaksi::whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        } else {
            $laporan = Transaksi::whereDate('kategori_id', $id_kategori)
                ->whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $dari)
                ->orderBy('id', 'desc')->get();
        }

        return view(
            'laporan_hasil',
            [
                'laporan' => $laporan,
                'dari' => $dari,
                'sampai' => $sampai,
                'kategori' => $kategori
            ]
        );
    }

    public function laporan_print(Request $req)
    {
        $req->validate([
            'dari' => 'required',
            'sampai' => 'required'
        ]);

        $kategori = Kategori::all();

        $dari = $req->dari;
        $sampai = $req->sampai;
        $id_kategori = $req->kategori;

        // Decode JSON to PHP array if necessary
        if (is_string($id_kategori)) {
            $id_kategori = json_decode($id_kategori, true);
        }

        // periksa kategori yang dipilih
        if (in_array("semua", array_column($id_kategori, 'id'))) {
            $laporan = Transaksi::whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        } else {
            $kategori_ids = array_column($id_kategori, 'id');
            $laporan = Transaksi::whereIn('kategori_id', $kategori_ids)
                ->whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        }

        return view('laporan_print', [
            'laporan' => $laporan,
            'kategori' => $kategori,
            'dari' => $dari,
            'sampai' => $sampai,
            'kat' => $id_kategori
        ]);
    }

    public function laporan_excel()
    {
        return Excel::download(new LaporanExport, 'laporan.xlsx');
    }

    public function ganti_password()
    {
        return view('gantipassword');
    }

    public function ganti_password_aksi(Request $request)
    {
        // periksa apakah inputan password sekarang ('current-password') sesusai dengan password sekarang
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // jika tidak sesuai, alihkan halaman kembali ke form ganti password
            // sambil mengirimkan pemberitahuan bahwa password tidak sesuai
            return redirect()->back()->with("error", "Password sekarang tidak sesuai");
        }
        // periksa jika password baru sama dengan password sekarang
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //jika password baru yang di inputkan sama dengan password lama
            return redirect()->back()->with("error", "Password baru tidak boleh sama dengan password sekarang");
        }
        // membuat form validasi
        $validateData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed'
        ]);
        // ganti password user yang sedang login dengan password baru
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user-> save();
        return redirect()->back()->with("success", "Password berhasil diganti");
    }
}
