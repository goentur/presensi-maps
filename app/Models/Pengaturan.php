<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $guarded = ['id'];

    public function tempat_kerja()
    {
        return $this->belongsTo(TempatKerja::class);
    }
}
