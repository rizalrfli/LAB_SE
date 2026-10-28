<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'nama',
        'nim',
        'email',
        'foto',
        'minat',
        'status',
        'tanggal_daftar',
        'tanggal_review',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
        'tanggal_review' => 'datetime',
    ];

    public function riwayat(): HasMany
    {
        return $this->hasMany(RiwayatPendaftaran::class, 'pendaftaran_id');
    }
}
