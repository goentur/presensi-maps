@extends('layouts.data')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="fs-3 text-uppercase">{{ $attribute['title'] }}</div>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route('home') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> KEMBALI KE MENU</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <h3>INFORMASI AKUN</h3>
                <table class="table-sm">
                    <tr>
                        <td class="w-5">EMAIL</td>
                        <td class="w-1">:</td>
                        <td>{{ $pegawai->user->email }}</td>
                    </tr>
                    <tr>
                        <td>NIP</td>
                        <td>:</td>
                        <td>{{ $pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <td>NAMA</td>
                        <td>:</td>
                        <td>{{ $pegawai->user->name }}</td>
                    </tr>
                    <tr>
                        <td>JABATAN</td>
                        <td>:</td>
                        <td>{{ $pegawai->jabatan->nama }}</td>
                    </tr>
                    <tr>
                        <td>TEMPAT KERJA</td>
                        <td>:</td>
                        <td>{{ $pegawai->tempat_kerja->nama }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6">
                <h3>UBAH PASSWORD</h3>
                <form action="{{route('akun.ubah-password')}}" method="post">
                    @csrf    
                    <label for="passwordLama" class="form-label text-uppercase">password lama <span class="text-danger">*</span></label>
                    <input required type="password" class="form-control @error('passwordLama')is-invalid @enderror" id="passwordLama" name="passwordLama" placeholder="Masukan passwordLama">
                    @error('passwordLama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <label for="password" class="form-label text-uppercase">password <span class="text-danger">*</span></label>
                    <input required type="password" class="form-control @error('password')is-invalid @enderror" id="password" name="password" placeholder="Masukan password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <label for="password_confirmation" class="form-label text-uppercase mt-3">konfirmasi password <span class="text-danger">*</span></label>
                    <input required type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password">
                    <button type="submit" class="btn btn-primary text-uppcase mt-3"><i class="fa fa-save"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection