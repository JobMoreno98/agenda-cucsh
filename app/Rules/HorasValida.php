<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HorasValida implements ValidationRule
{

    protected $horaMinima;
    protected $horaMaxima;
    protected $tipo;

    public function __construct($horaMinima, $horaMaxima, $tipo)
    {
        $this->horaMinima = $horaMinima;
        $this->horaMaxima = $horaMaxima;
        $this->tipo = $tipo;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->tipo == 'update') {
            $hora = \DateTime::createFromFormat('H:i:s', $value);
        } else {
            $hora = \DateTime::createFromFormat('H:i', $value);
        }
        // Verifica si la hora está dentro del rango
        $horaMinima = \DateTime::createFromFormat('H:i:s', $this->horaMinima);
        $horaMaxima = \DateTime::createFromFormat('H:i:s', $this->horaMaxima);
        //dd($hora,$horaMinima,$horaMaxima,($hora <= $horaMinima or $hora >= $horaMaxima));
        if ($hora <= $horaMinima or $hora >= $horaMaxima) {
            $fail('La hora debe estar entre ' . $this->horaMinima . ' y ' . $this->horaMaxima . '.');
        }
    }
}
