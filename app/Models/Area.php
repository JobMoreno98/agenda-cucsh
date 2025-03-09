<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = [];

    protected function sedeReal(): Attribute
    {
        $arreglo = ['normal' => 'La Normal', 'belenes' => 'Belenes', 'aulas' => 'Belenes Aulas'];
        return Attribute::make(
            get: fn() => $arreglo[$this->sede],
            set: fn() =>  $arreglo[$this->sede],
        );
    }
}
