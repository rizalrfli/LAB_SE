<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    protected $table = 'penelitian';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tahun',
        'status',
        'jumlah_tim',
        'peneliti_utama',
        'anggota_tim',
        'dosen_pembimbing',
        'kategori',
        'metodologi',
        'tanggal_mulai',
        'tanggal_selesai',
        'progress',
        'keywords',
        'link_dokumen',
        'output_hasil',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'jumlah_tim' => 'integer',
        'progress' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
}
