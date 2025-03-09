<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organizador extends Model
{
    protected $guarded = [];
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'areas_id', 'id');
    }
}
