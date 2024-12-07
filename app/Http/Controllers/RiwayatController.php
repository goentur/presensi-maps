<?php

namespace App\Http\Controllers;

use App\Enums\StatusPresensi;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    protected $attribute = [
        'view' => 'riwayat.',
        'link' => 'riwayat.',
        'title' => 'riwayat',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'attribute' => $this->attribute,
            'pegawai' => Pegawai::with('user')->where('user_id', auth()->id())->first(),
            'pegawais' => Pegawai::with('user')->get(),
            'bulan' => date('m-Y'),
        ];
        return view($this->attribute['view'] . 'index', $data);
    }
    public function data(Request $request): JsonResponse
    {
        $request->validate([
            'pegawai' => 'required|exists:pegawais,id',
            'bulan' => 'required|string',
        ]);
        if ($request->ajax()) {
            return response()->json(['data' => $this->proses($request->pegawai, $request->bulan), 'bulan' => $request->bulan], 200);
        }
    }
    protected function proses($pegawai, $bulan)
    {
        $bulanExplode = explode("-", $bulan);
        // Inisialisasi tanggal awal dan akhir
        $startDate = Carbon::create($bulanExplode[1], $bulanExplode[0], 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Ambil data PRESENSI MASUK
        $masukData = Presensi::select('tanggal', 'berkas', 'tipe', 'waktu', 'status')
            ->where('pegawai_id', $pegawai)
            ->where('tipe', 'PRESENSI MASUK')
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy('tanggal'); // Kelompokkan berdasarkan tanggal

        // Ambil data PRESENSI PULANG
        $keluarData = Presensi::select('tanggal', 'berkas', 'tipe', 'waktu', 'status')
            ->where('pegawai_id', $pegawai)
            ->where('tipe', 'PRESENSI PULANG')
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy('tanggal'); // Kelompokkan berdasarkan tanggal

        $result = [];
        $no = 1;
        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            // Nilai default untuk presensi masuk dan keluar
            $masuk = $keluar = '';
            $foto_masuk = $foto_pulang = '';

            if ($date->isWeekend()) {
                $masuk = $keluar = '<span class="badge bg-secondary">HARI LIBUR</span>';
            } else {
                // Cek jika data presensi masuk ada
                if ($masukData->has($dateStr)) {
                    $data = $masukData[$dateStr];
                    $status = StatusPresensi::tryFrom($data->status);
                    $masuk = '<span title="' . $status->value . '" class="badge bg-' . $status->color() . '">' . $data->waktu . '</span>';
                    $foto_masuk = '<a href="' . url('storage/' . $data->berkas) . '" target="popup" onclick="window.open(`' . url('storage/' . $data->berkas) . '`,`' . $data->value . '`,`width=800,height=600`)" class="btn btn-sm btn-primary"><i class="fa fa-camera"></i></a>';
                }
                // Cek jika data presensi pulang ada
                if ($keluarData->has($dateStr)) {
                    $data = $keluarData[$dateStr];
                    $status = StatusPresensi::tryFrom($data->status);
                    $keluar = '<span title="' . $status->value . '" class="badge bg-' . $status->color() . '">' . $data->waktu . '</span>';
                    $foto_pulang = '<a href="' . url('storage/' . $data->berkas) . '" target="popup" onclick="window.open(`' . url('storage/' . $data->berkas) . '`,`' . $data->value . '`,`width=800,height=600`)" class="btn btn-sm btn-primary"><i class="fa fa-camera"></i></a>';
                }
            }

            // Menyusun hasil untuk setiap tanggal
            $result[] = [
                'no' => $no++,
                'tanggal' => $dateStr,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'foto_masuk' => $foto_masuk,
                'foto_pulang' => $foto_pulang,
            ];
        }

        return $result;
    }
    public function cetak(Request $request)
    {
        $request->validate([
            'pegawai' => 'required|exists:pegawais,id',
            'bulan' => 'required|string',
        ]);
        $data = [
            'attribute' => $this->attribute,
            'bulan' => $request->bulan,
            'pegawai' => Pegawai::with('user', 'tempat_kerja', 'jabatan')->find($request->pegawai),
            'data' => $this->dataCetak($request->pegawai, $request->bulan),
        ];
        return view($this->attribute['view'] . 'cetak', $data);
    }
    protected function dataCetak($pegawai, $bulan)
    {
        $bulanExplode = explode("-", $bulan);
        // Inisialisasi tanggal awal dan akhir
        $startDate = Carbon::create($bulanExplode[1], $bulanExplode[0], 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Ambil data PRESENSI MASUK
        $masukData = Presensi::select('tanggal', 'berkas', 'tipe', 'waktu', 'status')
            ->where('pegawai_id', $pegawai)
            ->where('tipe', 'PRESENSI MASUK')
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy('tanggal'); // Kelompokkan berdasarkan tanggal

        // Ambil data PRESENSI PULANG
        $keluarData = Presensi::select('tanggal', 'berkas', 'tipe', 'waktu', 'status')
            ->where('pegawai_id', $pegawai)
            ->where('tipe', 'PRESENSI PULANG')
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy('tanggal'); // Kelompokkan berdasarkan tanggal

        $result = [];
        $no = 1;
        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            // Nilai default untuk presensi masuk dan keluar
            $masuk = $keluar = '';
            $foto_masuk = $foto_pulang = '';

            if ($date->isWeekend()) {
                $masuk = $keluar = '<span class="badge bg-secondary">HARI LIBUR</span>';
            } else {
                // Cek jika data presensi masuk ada
                if ($masukData->has($dateStr)) {
                    $data = $masukData[$dateStr];
                    $status = StatusPresensi::tryFrom($data->status);
                    $masuk = '<span title="' . $status->value . '" class="badge bg-' . $status->color() . '">' . $data->waktu . '</span>';
                    $foto_masuk = '<img src="' . url('storage/' . $data->berkas) . '" width="50%">';
                }
                // Cek jika data presensi pulang ada
                if ($keluarData->has($dateStr)) {
                    $data = $keluarData[$dateStr];
                    $status = StatusPresensi::tryFrom($data->status);
                    $keluar = '<span title="' . $status->value . '" class="badge bg-' . $status->color() . '">' . $data->waktu . '</span>';
                    $foto_pulang = '<img src="' . url('storage/' . $data->berkas) . '" width="50%">';
                }
            }

            // Menyusun hasil untuk setiap tanggal
            $result[] = [
                'no' => $no++,
                'tanggal' => $dateStr,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'foto_masuk' => $foto_masuk,
                'foto_pulang' => $foto_pulang,
            ];
        }

        return $result;
    }
}
