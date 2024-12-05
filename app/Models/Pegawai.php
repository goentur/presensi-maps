<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tempat_kerja()
    {
        return $this->belongsTo(TempatKerja::class);
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
