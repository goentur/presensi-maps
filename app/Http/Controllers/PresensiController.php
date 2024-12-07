<?php

namespace App\Http\Controllers;

use App\Enums\StatusPresensi;
use App\Models\Pegawai;
use App\Models\Pengaturan;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresensiController extends Controller
{
    protected $attribute = [
        'view' => 'presensi.',
        'link' => 'presensi.',
        'title' => 'presensi',
    ];

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pegawai = Pegawai::with('tempat_kerja')->where('user_id', auth()->id())->first();
        $currentTime = Carbon::now()->toTimeString();
        $data = [
            'attribute' => $this->attribute,
            'pegawai' => $pegawai,
            'pengaturan' => Pengaturan::whereTime('awal', '<=', $currentTime)
                ->whereTime('akhir', '>=', $currentTime)->where('tempat_kerja_id', $pegawai->tempat_kerja_id)
                ->first(),
        ];
        return view($this->attribute['view'] . 'index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pegawai' => 'required|exists:pegawais,id',
            'pengaturan' => 'required|exists:pengaturans,id',
            'tempatKerja' => 'required|exists:tempat_kerjas,id',
            'berkas' => 'required',
            'tanggal' => 'required|string|max:255',
            'waktu' => 'required|date_format:H:i:s',
            'koordinat' => 'required|string',
            'tipe' => 'required|string|max:255',
        ]);

        try {
            // cek presensi
            $presensi = Presensi::where([
                'pegawai_id' => $request->pegawai,
                'pengaturan_id' => $request->pengaturan,
                'tempat_kerja_id' => $request->tempatKerja,
                'tanggal' => $request->tanggal,
                'tipe' => $request->tipe,
            ])->first();
            if ($presensi) {
                return response()->json([
                    'status' => false,
                    'message' => 'ANDA SUDAH ' . $request->tipe . ' JAM : ' . $presensi->waktu,
                ]);
            }
            DB::beginTransaction();
            $img = $request->berkas;
            $folderPath = "berkas/foto/";
            $berkas_parts = explode(";base64,", $img);
            $berkas_base64 = base64_decode($berkas_parts[1]);
            $fileName = Str::uuid() . '.png';
            $pegawai = Pegawai::find($request->pegawai);
            $pengaturan = Pengaturan::whereTime('awal', '<=', $request->waktu)
                ->whereTime('terlambat', '>=', $request->waktu)->where('tempat_kerja_id', $pegawai->tempat_kerja_id)
                ->first();
            $status = StatusPresensi::MASUK;
            if ($pengaturan) {
                $status = StatusPresensi::TERLAMBAT;
            }
            Presensi::create([
                'pegawai_id' => $request->pegawai,
                'pengaturan_id' => $request->pengaturan,
                'tempat_kerja_id' => $request->tempatKerja,
                'berkas' => $folderPath . "" . $fileName,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'koordinat' => $request->koordinat,
                'tipe' => $request->tipe,
                'status' => $status,
            ]);
            DB::commit();
            Storage::put($folderPath . $fileName, $berkas_base64);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat melakukan transaksi penjualan',
            ]);
        }
    }
}
