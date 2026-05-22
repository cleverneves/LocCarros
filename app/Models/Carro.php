<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function rules(): array
    {
        return [
            'modelo_id' => 'required|exists:modelos,id',
            'placa'     => 'required|string|max:10',
            'disponivel'=> 'required|boolean',
            'km'        => 'required|integer|min:0',
        ];
    }

    public function modelo()
    {
        return $this->belongsTo('App\Models\Modelo');
    }
}
