<?php

namespace App\Http\Controllers;

use App\Enums\TipePengaturan;
use App\Models\Pengaturan;
use App\Models\TempatKerja;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class PengaturanController extends Controller
{
    protected $attribute = [
        'view' => 'pengaturan.',
        'link' => 'pengaturan.',
        'title' => 'pengaturan',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $builder)
    {
        if ($request->ajax()) {
            return DataTables::eloquent(Pengaturan::with('tempat_kerja')->select('id', 'tempat_kerja_id', 'tipe', 'keterangan', 'awal', 'terlambat', 'akhir'))
                ->addIndexColumn()
                ->editColumn('tipe', function (Pengaturan $data) {
                    return view($this->attribute['view'] . 'tipe', [
                        'data' => TipePengaturan::tryFrom($data->tipe)
                    ]);
                })
                ->addColumn('aksi', function (Pengaturan $data) {
                    $kirim = [
                        'data' => $data,
                        'attribute' => $this->attribute,
                    ];
                    return view($this->attribute['view'] . 'aksi', $kirim);
                })->make(true);
        }
        $dataTable = $builder
            ->addIndex(['class' => 'w-1 text-center', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'NO'])
            ->addColumn(['data' => 'tempat_kerja.nama', 'name' => 'tempat_kerja.nama', 'title' => 'TEMPAT KERJA'])
            ->addColumn(['class' => 'w-1', 'data' => 'tipe', 'name' => 'tipe', 'title' => 'TIPE'])
            ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'KETERANGAN'])
            ->addColumn(['class' => 'w-1', 'data' => 'awal', 'name' => 'awal', 'title' => 'AWAL'])
            ->addColumn(['class' => 'w-1', 'data' => 'terlambat', 'name' => 'terlambat', 'title' => 'TERLAMBAT'])
            ->addColumn(['class' => 'w-1', 'data' => 'akhir', 'name' => 'akhir', 'title' => 'AKHIR'])
            ->addColumn(['class' => 'w-1', 'data' => 'aksi', 'name' => 'aksi', 'title' => 'AKSI'])
            ->parameters([
                'ordering' => false,
                'responsive' => true,
                'bAutoWidth' => false,
                'lengthMenu' => [25, 50, 75, 100],
                'language' => [
                    'url' => asset('js/id.json'),
                ],
            ]);
        $data = [
            'attribute' => $this->attribute,
            'dataTable' => $dataTable,
        ];
        return view($this->attribute['view'] . 'index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'attribute' => $this->attribute,
            'tempatKerjas' => TempatKerja::all(),
            'tipes' => TipePengaturan::cases(),
        ];
        return view($this->attribute['view'] . 'form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tempatKerja' => 'required|exists:tempat_kerjas,id',
            'tipe' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'awal' => 'required|date_format:H:i',
            'terlambat' => 'required|date_format:H:i',
            'akhir' => 'required|date_format:H:i',
        ]);
        Pengaturan::create([
            'tempat_kerja_id' => $request->tempatKerja,
            'tipe' => $request->tipe,
            'keterangan' => $request->keterangan,
            'awal' => $request->awal,
            'terlambat' => $request->terlambat,
            'akhir' => $request->akhir,
        ]);
        return redirect()->route($this->attribute['link'] . 'index')->with(['success' => 'Data berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengaturan $pengaturan)
    {
        return abort('404');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengaturan $pengaturan)
    {
        $kirim = [
            'attribute' => $this->attribute,
            'data' => $pengaturan,
            'tempatKerjas' => TempatKerja::all(),
            'tipes' => TipePengaturan::cases(),
        ];
        return view($this->attribute['view'] . 'form', $kirim);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengaturan $pengaturan)
    {
        $request->validate([
            'tempatKerja' => 'required|exists:tempat_kerjas,id',
            'tipe' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'awal' => 'required|date_format:H:i',
            'terlambat' => 'required|date_format:H:i',
            'akhir' => 'required|date_format:H:i',
        ]);
        $pengaturan->update([
            'tempat_kerja_id' => $request->tempatKerja,
            'tipe' => $request->tipe,
            'keterangan' => $request->keterangan,
            'awal' => $request->awal,
            'terlambat' => $request->terlambat,
            'akhir' => $request->akhir,
        ]);
        return redirect()->route($this->attribute['link'] . 'index')->with(['success' => 'Data berhasil diubah']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);
        if ($request->ajax()) {
            Pengaturan::select('id')->find($request->id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus.',
            ]);
        }
    }
}
