<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';

    protected $fillable = [
        'cliente_id',
        'carro_id',
        'data_inicio_periodo',
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final',
    ];

    public function rules(): array
    {
        return [
            'cliente_id'                   => 'required|exists:clientes,id',
            'carro_id'                     => 'required|exists:carros,id',
            'data_inicio_periodo'          => 'required|date',
            'data_final_previsto_periodo'  => 'required|date|after:data_inicio_periodo',
            'data_final_realizado_periodo' => 'nullable|date|after:data_inicio_periodo',
            'valor_diaria'                 => 'required|numeric|min:0',
            'km_inicial'                   => 'required|integer|min:0',
            'km_final'                     => 'nullable|integer|min:0',
        ];
    }
}
