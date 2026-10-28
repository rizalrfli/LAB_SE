<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontenPage extends Model
{
    protected $table = 'konten_page';

    public $timestamps = false;

    protected $fillable = [
        'page_key',
        'title',
        'content',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];
}
