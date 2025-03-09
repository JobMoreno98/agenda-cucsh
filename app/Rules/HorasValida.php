<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HorasValida implements ValidationRule
{

    protected $horaMinima;
    protected $horaMaxima;

    public function __construct($horaMinima, $horaMaxima)
    {
        $this->horaMinima = $horaMinima;
        $this->horaMaxima = $horaMaxima;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $hora = \DateTime::createFromFormat('H:i:s', $value);
        // Verifica si la hora está dentro del rango
        $horaMinima = \DateTime::createFromFormat('H:i:s', $this->horaMinima);
        $horaMaxima = \DateTime::createFromFormat('H:i:s', $this->horaMaxima);
        //dd($hora,$horaMinima,$horaMaxima,($hora <= $horaMinima or $hora >= $horaMaxima));
        if($hora <= $horaMinima or $hora >= $horaMaxima){
            $fail('La hora debe estar entre ' . $this->horaMinima . ' y ' . $this->horaMaxima . '.');
        }

    }
}
