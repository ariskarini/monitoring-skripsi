<?php

namespace App\Http\Controllers;

use App\Models\BimbinganSkripsi;
use App\Models\JadwalUjian;
use App\Models\Mahasiswa;
use App\Models\Pembimbing;
use App\Models\PengajuanSkripsi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $jumlahDosen = Pembimbing::count();
        $jumlahMahasiswa = Mahasiswa::count();
        if (auth()->user()->role == 'mahasiswa') {
            $mahasiswaId = auth()->user()->mahasiswa->id;
            $bimbingan = BimbinganSkripsi::where('mahasiswa_id', $mahasiswaId)->get();
            $jumlahBimbingan = BimbinganSkripsi::where('mahasiswa_id', $mahasiswaId)->count();
            $statusBimbingan = BimbinganSkripsi::where('mahasiswa_id', $mahasiswaId)->first()->status ?? '-';
            $terakhirBimbingan = BimbinganSkripsi::where('mahasiswa_id', $mahasiswaId)->latest()->first();
            $judulDiterima = PengajuanSkripsi::where('mahasiswa_id', $mahasiswaId)->where('status', 'diterima')->first();
            $jadwalUjian = JadwalUjian::where('mahasiswa_id', $mahasiswaId)->get();
        
            return view('home', compact('jumlahBimbingan', 'bimbingan', 'statusBimbingan', 'terakhirBimbingan', 'judulDiterima', 'jadwalUjian'));
        }
        

        if (Auth::user()->role == 'pembimbing') {
            $pembimbingId = auth()->user()->pembimbing->id;

            $jadwalMenguji = JadwalUjian::where(function ($query) use ($pembimbingId) {
                $query->where('penguji1_id', $pembimbingId)
                    ->orWhere('penguji2_id', $pembimbingId);
            })->where('tanggal', '>', Carbon::today())
                ->where('status', 'diterima')
                ->get();
            $jumlahMahasiswaBimbingan = Mahasiswa::where('pembimbing_id', $pembimbingId)->count();
            $jumlahSidangProposal = JadwalUjian::where('kategori', 'Proposal')
                ->where(function ($query) use ($pembimbingId) {
                    $query->where('penguji1_id', $pembimbingId)
                        ->orWhere('penguji2_id', $pembimbingId);
                })->count();

            $jumlahSidangPendadaran = JadwalUjian::where('kategori', 'Pendadaran')
                ->where(function ($query) use ($pembimbingId) {
                    $query->where('penguji1_id', $pembimbingId)
                        ->orWhere('penguji2_id', $pembimbingId);
                })->count();

            return view('home', compact('jadwalMenguji', 'jumlahMahasiswaBimbingan', 'jumlahSidangProposal', 'jumlahSidangPendadaran'));
        }
        return view('home', compact('jumlahDosen', 'jumlahMahasiswa'));
    }
}
