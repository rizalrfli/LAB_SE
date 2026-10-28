<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoadmapItem extends Model
{
    protected $table = 'roadmap_item';

    protected $fillable = [
        'id_periode',
        'deskripsi_tugas',
        'urutan',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(RoadmapPeriode::class, 'id_periode');
    }
}
