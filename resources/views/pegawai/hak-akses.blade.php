@if ($data->user->roles->pluck('name')[0] == 'admin')
    <span class="badge bg-primary">ADMIN</span>
@else
    <span class="badge bg-success">PEGAWAI</span>
@endif