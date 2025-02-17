@extends('layouts.data')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="fs-3 text-uppercase">Data {{ $attribute['title'] }}</div>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route('home') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> KEMBALI KE MENU</a>
                {{-- <a href="{{ route($attribute['link'].'create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> TAMBAH DATA</a> --}}
            </div>
        </div>
    </div>
    <div class="card-body">
        {{ $dataTable->table(['class' => 'table table-bordered table-hover table-sm']) }}
    </div>
</div>
@endsection
@push('js')
{{ $dataTable->scripts() }}
<script>
    $(document).on("click", ".hapus", function() {
        var t = $(this).data("id");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "ingin mengahapus data ini!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#0d6efd",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route($attribute['link'].'destroy',csrf_token()) }}",
                    type: "POST",
                    data: {
                        _method: "DELETE",
                        id: t
                    },
                    dataType: "JSON",
                    success: function(t) {
                        t.status ? (alertApp("success", t.message), $("#dataTableBuilder").DataTable().ajax.reload()) : alertApp("error", t.message)
                    },
                    error: function(t, a, e) {
                        alertApp("error", e)
                    }
                });
            }
        });
    })
</script>
@endpush