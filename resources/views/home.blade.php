@extends('layouts.menu')

@section('content')
<div class="row gap-3 justify-content-center">
    <div class="col-12 bg-white text-center rounded">
        <span class="fs-1 fw-bold">SELAMAT DATANG</span>
        <br>
        <span class="fs-2 text-uppercase">DI SISTEM INFORMASI PRESENSI {{ Auth::user()->name }}</span>
    </div>
    @role(['admin','pegawai'])
    <div class="col-4 col-lg-2 card bg-white shadow-lg text-center">
        <img class="img-fluid p-3" src="{{ asset('images/menus/presensi.svg') }}" alt="Menu Presensi" srcset="Menu Presensi">
        <p class="fw-bold text-uppercase fs-2">presensi</p>
        <a href="{{ route('presensi.index') }}" class="stretched-link"></a>
    </div>
    <div class="col-4 col-lg-2 card bg-white shadow-lg text-center">
        <img class="img-fluid p-3" src="{{ asset('images/menus/riwayat.svg') }}" alt="Menu Riwayat" srcset="Menu Riwayat">
        <p class="fw-bold text-uppercase fs-2">riwayat</p>
        <a href="{{ route('riwayat.index') }}" class="stretched-link"></a>
    </div>
    @endrole
    @role(['dev','admin'])
    <div class="col-4 col-lg-2 card bg-white shadow-lg text-center">
        <img class="img-fluid p-3" src="{{ asset('images/menus/lokasi.svg') }}" alt="Menu Tempat Kerja" srcset="Menu Tempat Kerja">
        <p class="fw-bold text-uppercase fs-2">Tempat Kerja</p>
        <a href="{{ route('tempat-kerja.index') }}" class="stretched-link"></a>
    </div>
    <div class="col-4 col-lg-2 card bg-white shadow-lg text-center">
        <img class="img-fluid p-3" src="{{ asset('images/menus/jabatan.svg') }}" alt="Menu Jabatan" srcset="Menu Jabatan">
        <p class="fw-bold text-uppercase fs-2">jabatan</p>
        <a href="{{ route('jabatan.index') }}" class="stretched-link"></a>
    </div>
    <div class="col-4 col-lg-2 card bg-white shadow-lg text-center">
        <img class="img-fluid p-3" src="{{ asset('images/menus/pengaturan.svg') }}" alt="Menu Pengaturan" srcset="Menu Pengaturan">
        <p class="fw-bold text-uppercase fs-2">pengaturan</p>
        <a href="{{ route('pengaturan.index') }}" class="stretched-link"></a>
    </div>
    <div class="col-4 col-lg-2 card bg-white shadow-lg text-center">
        <img class="img-fluid p-3" src="{{ asset('images/menus/pegawai.svg') }}" alt="Menu Pegawai" srcset="Menu Pegawai">
        <p class="fw-bold text-uppercase fs-2">pegawai</p>
        <a href="{{ route('pegawai.index') }}" class="stretched-link"></a>
    </div>
    @endrole
    @role(['admin','pegawai'])
    <div class="col-4 col-lg-2 card bg-white shadow-lg text-center">
        <img class="img-fluid p-3" src="{{ asset('images/menus/akun.svg') }}" alt="Menu Akun" srcset="Menu Akun">
        <p class="fw-bold text-uppercase fs-2">akun</p>
        <a href="{{ route('akun.index') }}" class="stretched-link"></a>
    </div>
    @endrole
    <div class="col-4 col-lg-2 card bg-white shadow-lg text-center">
        <img class="img-fluid p-3" src="{{ asset('images/menus/logout.svg') }}" alt="Menu Keluar" srcset="Menu Keluar">
        <p class="fw-bold text-uppercase fs-2">keluar</p>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="stretched-link"></a><form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</div>
@endsection