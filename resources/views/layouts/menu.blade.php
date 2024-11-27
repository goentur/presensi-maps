@extends('layouts.app')

@section('contents')
<div style="height: 100vh" class="d-flex justify-content-center">
    <div class="container align-self-center">
        @yield('content')
    </div>
</div>
@endsection