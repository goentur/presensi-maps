@extends('layouts.data')

@push('vendor-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="fs-3 text-uppercase">Form {{ $attribute['title'] }}</div>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route($attribute['link'].'index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> KEMBALI DATA</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h5 class="card-title">INFORMASI</h5>
            <h6 class="card-subtitle text-muted">Form yang bertanda (<span class="text-danger">*</span>) <b>wajib</b> diisi.</h6>
        </div>
        <form action="{{ isset($data)?route($attribute['link'].'update',$data->id):route($attribute['link'].'store') }}" method="post">
            @csrf
            @isset($data)
            @method('PUT')
            @endisset
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label for="nip" class="form-label text-uppercase">nip <span class="text-danger">*</span></label>
                    <input required type="text" class="form-control @error('nip')is-invalid @enderror" value="{{ isset($data)?$data->nip:old('nip') }}" id="nip" name="nip" placeholder="Masukan nip">
                    @error('nip')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-lg-8 mb-3">
                    <label for="nama" class="form-label text-uppercase">nama <span class="text-danger">*</span></label>
                    <input required type="text" class="form-control @error('nama')is-invalid @enderror" value="{{ isset($data)?$data->nama:old('nama') }}" id="nama" name="nama" placeholder="Masukan nama">
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="tempatKerja" class="form-label text-uppercase">Tempat Kerja <span class="text-danger">*</span></label>
                    <select required name="tempatKerja" id="tempatKerja" class="form-control select2">
                        <option value="">Pilih salah satu</option>
                        @foreach ($tempatKerjas as $tempatKerja)
                        <option value="{{ $tempatKerja->id }}" {{ isset($data)&&$data->tempatKerja_id==$tempatKerja->id?' selected':''}}{{old('tempatKerja')==$tempatKerja->id?' selected':'' }}>{{ $tempatKerja->nama }}</option>
                        @endforeach
                    </select>
                    @error('tempatKerja')
                    <strong class="text-danger f-12">{{ $message }}</strong>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="jabatan" class="form-label text-uppercase">jabatan <span class="text-danger">*</span></label>
                    <select required name="jabatan" id="jabatan" class="form-control select2">
                        <option value="">Pilih salah satu</option>
                        @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ isset($data)&&$data->jabatan_id==$jabatan->id?' selected':''}}{{old('jabatan')==$jabatan->id?' selected':'' }}>{{ $jabatan->nama }}</option>
                        @endforeach
                    </select>
                    @error('jabatan')
                    <strong class="text-danger f-12">{{ $message }}</strong>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="email" class="form-label text-uppercase">email <span class="text-danger">*</span></label>
                    <input required type="email" class="form-control @error('email')is-invalid @enderror" value="{{ isset($data)?$data->email:old('email') }}" id="email" name="email" placeholder="Masukan email">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="password" class="form-label text-uppercase">password <span class="text-danger">*</span></label>
                    <input required type="password" class="form-control @error('password')is-invalid @enderror" id="password" name="password" placeholder="Masukan password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <label for="password_confirmation" class="form-label text-uppercase mt-3">konfirmasi password <span class="text-danger">*</span></label>
                    <input required type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary text-uppcase"><i class="fa fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script>
$('#tempatKerja').select2({
    theme: 'bootstrap-5'
});
$('#jabatan').select2({
    theme: 'bootstrap-5'
});
</script>
@endpush