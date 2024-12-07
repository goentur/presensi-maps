<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\TempatKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class PegawaiController extends Controller
{
    protected $attribute = [
        'view' => 'pegawai.',
        'link' => 'pegawai.',
        'title' => 'pegawai',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $builder)
    {
        if ($request->ajax()) {
            return DataTables::eloquent(Pegawai::with('user', 'tempat_kerja', 'jabatan')->select('id', 'nip', 'user_id', 'tempat_kerja_id', 'jabatan_id'))
                ->addIndexColumn()
                ->addColumn('hak_akses', function (Pegawai $data) {
                    $kirim = [
                        'data' => $data,
                    ];
                    return view($this->attribute['view'] . 'hak-akses', $kirim);
                })
                ->addColumn('aksi', function (Pegawai $data) {
                    $kirim = [
                        'data' => $data,
                        'attribute' => $this->attribute,
                    ];
                    return view($this->attribute['view'] . 'aksi', $kirim);
                })->make(true);
        }
        $dataTable = $builder
            ->addIndex(['class' => 'w-1 text-center', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'NO'])
            ->addColumn(['data' => 'user.email', 'name' => 'user.email', 'title' => 'EMAIL'])
            ->addColumn(['class' => 'w-1', 'data' => 'nip', 'name' => 'nip', 'title' => 'NIP'])
            ->addColumn(['data' => 'user.name', 'name' => 'user.name', 'title' => 'NAMA'])
            ->addColumn(['data' => 'jabatan.nama', 'name' => 'jabatan.nama', 'title' => 'JABATAN'])
            ->addColumn(['data' => 'tempat_kerja.nama', 'name' => 'tempat_kerja.nama', 'title' => 'TEMPAT KERJA'])
            ->addColumn(['class' => 'text-center', 'data' => 'hak_akses', 'name' => 'hak_akses', 'title' => 'HAK AKSES'])
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
            'jabatans' => Jabatan::all(),
        ];
        return view($this->attribute['view'] . 'form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempatKerja' => 'required|exists:tempat_kerjas,id',
            'jabatan' => 'required|exists:jabatans,id',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'hak_akses' => 'required|in:admin,pegawai',
        ]);
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->hak_akses);
        Pegawai::create([
            'nip' => $request->nip,
            'user_id' => $user->id,
            'tempat_kerja_id' => $request->tempatKerja,
            'jabatan_id' => $request->jabatan,
        ]);
        return redirect()->route($this->attribute['link'] . 'index')->with(['success' => 'Data berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $model)
    {
        return abort('404');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        $kirim = [
            'attribute' => $this->attribute,
            'data' => $pegawai,
            'tempatKerjas' => TempatKerja::all(),
            'jabatans' => Jabatan::all(),
        ];
        return view($this->attribute['view'] . 'form', $kirim);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nip' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempatKerja' => 'required|exists:tempat_kerjas,id',
            'jabatan' => 'required|exists:jabatans,id',
            'email' => 'required|string|email|max:255|unique:users,email,' . $pegawai->user_id,
            'hak_akses' => 'required|in:admin,pegawai',
        ]);
        $pegawai->user->update([
            'name' => $request->nama,
        ]);
        $pegawai->user->syncRoles($request->hak_akses);
        $pegawai->update([
            'nip' => $request->nip,
            'tempat_kerja_id' => $request->tempatKerja,
            'jabatan_id' => $request->jabatan,
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
            $pegawai = Pegawai::find($request->id);
            $pegawai->user->delete();
            $pegawai->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus.',
            ]);
        }
    }
}
