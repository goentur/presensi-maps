<?php

namespace App\Http\Controllers;

use App\Models\TempatKerja;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class TempatKerjaController extends Controller
{
    protected $attribute = [
        'view' => 'tempat-kerja.',
        'link' => 'tempat-kerja.',
        'title' => 'tempat-kerja',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $builder)
    {
        if ($request->ajax()) {
            return DataTables::eloquent(TempatKerja::select('id', 'nama', 'koordinat'))
                ->addIndexColumn()
                ->addColumn('aksi', function (TempatKerja $data) {
                    $kirim = [
                        'data' => $data,
                        'attribute' => $this->attribute,
                    ];
                    return view($this->attribute['view'] . 'aksi', $kirim);
                })->make(true);
        }
        $dataTable = $builder
            ->addIndex(['class' => 'w-1 text-center', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'NO'])
            ->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'NAMA'])
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
            'jmlTempatKerja' => TempatKerja::select('id')->get(),
        ];
        return view($this->attribute['view'] . 'index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jmlTempatKerja = TempatKerja::select('id')->get();
        if (count($jmlTempatKerja) < 2) {
            $data = [
                'attribute' => $this->attribute,
            ];
            return view($this->attribute['view'] . 'form', $data);
        } else {
            return back()->with(['error' => 'Tempat kerja sudah ditambahkan']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        TempatKerja::create([
            'nama' => $request->nama,
            'koordinat' => '[[{"lat":-6.762804242050369,"lng":109.52064514160158},{"lat":-6.89913985537154,"lng":109.83032226562501},{"lat":-7.054568026786724,"lng":109.7527313232422},{"lat":-7.0286761724241815,"lng":109.45266723632812}]]',
        ]);
        return redirect()->route($this->attribute['link'] . 'index')->with(['success' => 'Data berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(TempatKerja $tempatKerja)
    {
        $kirim = [
            'attribute' => $this->attribute,
            'data' => $tempatKerja,
        ];
        return view($this->attribute['view'] . 'ubah-koordinat', $kirim);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TempatKerja $tempatKerja)
    {
        $kirim = [
            'attribute' => $this->attribute,
            'data' => $tempatKerja,
        ];
        return view($this->attribute['view'] . 'form', $kirim);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TempatKerja $tempatKerja)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $tempatKerja->update([
            'nama' => $request->nama,
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
            TempatKerja::select('id')->find($request->id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus.',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function ubahKoordinat(Request $request, TempatKerja $tempatKerja)
    {
        $request->validate([
            'koordinat' => 'required|string',
        ]);
        $tempatKerja->update([
            'koordinat' => $request->koordinat,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diubah.',
        ]);
    }
}
