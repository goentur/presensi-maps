<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    protected $attribute = [
        'view' => 'akun.',
        'link' => 'akun.',
        'title' => 'akun',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'attribute' => $this->attribute,
            'pegawai' => Pegawai::with('user', 'tempat_kerja', 'jabatan')->where('user_id', auth()->id())->first(),
        ];
        return view($this->attribute['view'] . 'index', $data);
    }
    public function ubahPassword(Request $request)
    {
        $request->validate([
            'passwordLama' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if (!Hash::check($request->passwordLama, auth()->user()->password)) {
            return back()->with("error", "Pasword lama tidak cocok!");
        }
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with("success", "Password berhasil diubah!");
    }
}
