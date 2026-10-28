<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    protected $table = 'publikasi';

    protected $fillable = [
        'judul',
        'tipe',
        'tahun',
        'penulis_utama',
        'daftar_penulis',
        'nama_journal_conference',
        'volume',
        'issue',
        'halaman',
        'doi',
        'abstract',
        'keywords',
        'link_url',
        'status',
        'scopus_indexed',
        'quartile',
        'tanggal_publikasi',
        'sitasi',
        'tautan_scholar',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'sitasi' => 'integer',
        'scopus_indexed' => 'boolean',
        'tanggal_publikasi' => 'date',
    ];
}
