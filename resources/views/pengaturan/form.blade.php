@extends('layouts.data')

@push('vendor-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                <div class="col-lg-6 mb-3">
                    <label for="tempatKerja" class="form-label text-uppercase">Tempat Kerja <span class="text-danger">*</span></label>
                    <select required name="tempatKerja" id="tempatKerja" class="form-control select2">
                        <option value="">Pilih salah satu</option>
                        @foreach ($tempatKerjas as $tempatKerja)
                        <option value="{{ $tempatKerja->id }}" {{ isset($data)&&$data->tempat_kerja_id==$tempatKerja->id?' selected':''}}{{old('tempatKerja')==$tempatKerja->id?' selected':'' }}>{{ $tempatKerja->nama }}</option>
                        @endforeach
                    </select>
                    @error('tempatKerja')
                    <strong class="text-danger f-12">{{ $message }}</strong>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="tipe" class="form-label text-uppercase">Tipe <span class="text-danger">*</span></label>
                    <select required name="tipe" id="tipe" class="form-control select2">
                        <option value="">Pilih salah satu</option>
                        @foreach ($tipes as $tipe)
                        <option value="{{ $tipe->value }}" {{ isset($data)&&$data->tipe==$tipe->value?' selected':''}}{{old('tipe')==$tipe->value?' selected':'' }}>{{ $tipe->value }}</option>
                        @endforeach
                    </select>
                    @error('tipe')
                    <strong class="text-danger f-12">{{ $message }}</strong>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="keterangan" class="form-label text-uppercase">keterangan <span class="text-danger">*</span></label>
                    <textarea name="keterangan" required id="keterangan" rows="3" class="form-control @error('keterangan')is-invalid @enderror" placeholder="Masukan keterangan">{{ isset($data)?$data->keterangan:old('keterangan') }}</textarea>
                    @error('keterangan')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-lg-2 mb-3">
                    <label for="awal" class="form-label text-uppercase">awal <span class="text-danger">*</span></label>
                    <input required type="text" class="form-control waktu @error('awal')is-invalid @enderror" value="{{ isset($data)?$data->awal:old('awal') }}" id="awal" name="awal" placeholder="Masukan awal">
                    @error('awal')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-lg-2 mb-3">
                    <label for="terlambat" class="form-label text-uppercase">terlambat <span class="text-danger">*</span></label>
                    <input required type="text" class="form-control waktu @error('terlambat')is-invalid @enderror" value="{{ isset($data)?$data->terlambat:old('terlambat') }}" id="terlambat" name="terlambat" placeholder="Masukan terlambat">
                    @error('terlambat')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-lg-2 mb-3">
                    <label for="akhir" class="form-label text-uppercase">akhir <span class="text-danger">*</span></label>
                    <input required type="text" class="form-control waktu @error('akhir')is-invalid @enderror" value="{{ isset($data)?$data->akhir:old('akhir') }}" id="akhir" name="akhir" placeholder="Masukan akhir">
                    @error('akhir')
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
@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>\
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$('#tempatKerja').select2({
    theme: 'bootstrap-5'
});
$('#tipe').select2({
    theme: 'bootstrap-5'
});
$(".waktu").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
});
</script>
@endpush