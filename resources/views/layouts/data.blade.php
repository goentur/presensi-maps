@extends('layouts.app')

@push('vendor-css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css">
@endpush
@section('contents')
<div class="container mt-5">
    @yield('content')
</div>
@endsection
@push('vendor-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script>
    @if ($message = session()->get('success'))
        $(function() {
            alertApp("success","{{ $message }}");
        });
    @endif
    @if ($message = session()->get('error'))
        $(function() {
            alertApp("error","{{ $message }}");
        });
    @endif
</script>
@endpush