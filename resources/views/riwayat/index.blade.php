@extends('layouts.data')

@push('vendor-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
@endpush
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="fs-3 text-uppercase">Data {{ $attribute['title'] }} presensi</div>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route('home') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> KEMBALI KE MENU</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form class="row mb-2" action="{{route($attribute['link'].'cetak')}}" target="_blank" method="post">
            @csrf
            <div class="col-lg-5">
                <label class="form-label h3"><i class="fa fa-user-alt"></i> PEGAWAI :</label>
                <select required name="pegawai" class="form-control" id="pegawai">
                    <option value="">Pilih salah satu</option>
                    @role('admin')
                    @foreach ($pegawais as $value)
                    <option {{ isset($pegawai)&&$pegawai->id==$value->id?' selected':''}} value="{{ $value->id }}">{{ $value->nip }} - {{ $value->user->name }}</option>
                    @endforeach
                    @endrole
                    @unlessrole('admin')
                    <option selected value="{{ $pegawai->id }}">{{ $pegawai->nip }} - {{ $pegawai->user->name }}</option>
                    @endunlessrole
                </select>
            </div>
            <div class="col-lg-3">
                <label class="form-label h3"><i class="fa fa-calendar-alt"></i> BULAN :</label>
                <input type="text" class="form-control" id="bulan" name="bulan" placeholder="Pilih bulan riwayat">
            </div>
            <div class="col-lg-2 d-grid">
                <label class="form-label h3">&nbsp; </label>
                <button type="button" id="btnCari" class="btn btn-primary"><i class="fa fa-search"></i> CARI DATA</button>
            </div>
            <div class="col-lg-2 d-grid">
                <label class="form-label h3">&nbsp; </label>
                <button type="submit" class="btn btn-success"><i class="fa fa-print"></i> CETAK</a>
            </div>
        </form>
    </div>
    <div class="card-body">
        <h4 class="text-center">PERIODE PRESENSI</h4>
        <h3 class="text-center">
            <span id="textBulan">{{ $bulan }}</span>
        </h3>
        @role('admin')
        <div class="accordion" id="accordionPanelsStayOpenExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                    FORM PRESENSI
                </button>
                </h2>
                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                <div class="accordion-body">
                    <form action="javascript:void(0)" id="formPresensi" class="row" method="POST" enctype="multipart/form-data">
                        <div class="col-lg-3">
                            <label class="form-label h5"><i class="fa fa-list"></i> TIPE PRESENSI :</label>
                            <select required name="tipe" id="tipe" class="form-control select2">
                                <option value="">Pilih salah satu</option>
                                @foreach ($tipes as $tipe)
                                <option value="{{ $tipe->value }}">{{ $tipe->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label h5"><i class="fa fa-calendar-alt"></i> TANGGAL :</label>
                            <input type="text" class="form-control" required id="tanggal" name="tanggal" placeholder="Pilih tanggal presensi">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label h5"><i class="fa fa-camera"></i> FOTO :</label>
                            <input class="form-control" required type="file" name="file" id="file">
                        </div>
                        <div class="col-lg-3 d-grid">
                            <label class="form-label h5">&nbsp; </label>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> SIMPAN</a>
                        </div>
                    </form>
                    <div class="text-danger text-bold" id="responsFormPresensi"></div>
                </div>
                </div>
            </div>
        </div>
        @endrole
        <table id="data" class="table table-sm table-bordered table-hover dt-responsive">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>MASUK</th>
                    <th>PULANG</th>
                    <th>FOTO MASUK</th>
                    <th>FOTO PULANG</th>
                    <th>AKSI</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>\
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    function data(pegawai, a) {
        $("table#data").DataTable({
            ordering: !1,
            responsive: !0,
            bAutoWidth: !1,
            paging: false, // Disable pagination
            info: false,    // Disable table info
            language: {
                url: "{{ asset('js/id.json') }}"
            },
            bDestroy: !0,
            processing: !0,
            ajax: {
                url: "{{ route($attribute['link'].'data') }}",
                type: "POST",
                data: {
                    pegawai: pegawai,
                    bulan: a
                },
                error: function(a, t, n) {
                    alertApp("error", "Data riwayat presensi tidak bisa ditampilkan.")
                }
            },
            columns: [{
                className: "w-1 text-center",
                data: "no"
            }, {
                data: "tanggal"
            }, {
                className: "w-5 text-center",
                data: "masuk"
            }, {
                className: "w-5 text-center",
                data: "keluar"
            }, {
                className: "text-center",
                data: "foto_masuk"
            }, {
                className: "text-center",
                data: "foto_pulang"
            }, {
                className: "w-1 text-center",
                data: "aksi"
            }],
            initComplete: function(a, t) {
                $("#textBulan").html(t.bulan)
            }
        })
    }
    $(function() {        
        flatpickr("#bulan", {
            locale: "id",
            dateFormat: "m-Y",
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "m-Y",
                    altFormat: "m-Y",
                })
            ],
            defaultDate: "{{ $bulan }}",
            maxDate: "{{ $bulan }}"
        }),flatpickr("#tanggal", {
            locale: "id",
            dateFormat: "d-m-Y",
            maxDate: "today",
        }), data("{{ $pegawai->id }}", "{{ $bulan }}");


        $('#formPresensi').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            var pegawai = $("#pegawai").val();
            var bulan = $("#bulan").val();
            let formData = new FormData(this); // Automatically includes all form fields and files
            formData.append('pegawai', pegawai);
            $.ajax({
                url: "{{ route($attribute['link'].'simpan-presensi') }}", // Laravel route
                type: 'POST',
                data: formData,
                processData: false, // Required for file uploads
                contentType: false, // Required for file uploads
                success: function (response) {
                    if (response.status) {
                        alertApp("success", response.message)
                        data(pegawai, bulan)
                    } else {
                        alertApp("error", response.message)
                    }
                },
                error: function (xhr) {
                    // Handle validation or server errors
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '<ul>';
                    $.each(errors, function (key, value) {
                        errorMessages += `<li>${value}</li>`;
                    });
                    errorMessages += '</ul>';
                    $('#responsFormPresensi').html(`<h4>ERRORS : </h4>${errorMessages}`);
                }
            });
        });
    });
    $("button#btnCari").on("click", function() {
        var pegawai = $("#pegawai").val();
        var a = $("#bulan").val();
        pegawai && a ? data(pegawai, a) : alertApp("error", "Pilih pegawai dan bulan.")
    });
    $('#pegawai').select2({
        theme: 'bootstrap-5'
    });
    function handleAction(action, tanggal) {
        var pegawai = $("#pegawai").val();
        var a = $("#bulan").val();
        $.ajax({
            url: "{{ route($attribute['link'].'validasi-presensi') }}", // Laravel route
            type: 'POST',
            data: {
                action:action,
                pegawai:pegawai,
                tanggal:tanggal,
            },
            success: function (response) {
                if (response.status) {
                    alertApp("success", response.message)
                    data(pegawai, a)
                } else {
                    alertApp("error", response.message)
                }
            },
            error: function (xhr) {
                // Handle validation or server errors
                let errors = xhr.responseJSON.errors;
                alertApp("error", errors)
            }
        });
    }
</script>
@endpush