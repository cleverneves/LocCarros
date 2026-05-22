<?php

namespace App\Repositories;

use App\Models\Carro;

class CarroRepository extends AbstractRepository
{
    public function __construct(Carro $carro)
    {
        parent::__construct($carro);
    }
}
