<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eventos extends Model
{
    use SoftDeletes;
    
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'areas_id','id');
    }
    public function organiza(): BelongsTo
    {
        return $this->belongsTo(Organizador::class, 'organizador_id','id');
    }
}
