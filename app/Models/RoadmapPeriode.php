<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoadmapPeriode extends Model
{
    protected $table = 'roadmap_periode';

    protected $fillable = [
        'tahun_periode',
        'judul_fase',
        'urutan',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(RoadmapItem::class, 'id_periode')->orderBy('urutan', 'asc');
    }
}
