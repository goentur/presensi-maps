@extends('layouts.data')

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
            <div class="row mb-3">
                <div class="col-lg-12">
                    <label for="nama" class="form-label text-uppercase">nama <span class="text-danger">*</span></label>
                    <input required type="text" class="form-control @error('nama')is-invalid @enderror" value="{{ isset($data)?$data->nama:old('nama') }}" id="nama" name="nama" placeholder="Masukan nama">
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary text-uppcase"><i class="fa fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection