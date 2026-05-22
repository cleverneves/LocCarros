<?php

namespace App\Repositories;

use App\Models\Modelo;

class ModeloRepository extends AbstractRepository
{
    public function __construct(Modelo $modelo)
    {
        parent::__construct($modelo);
    }
}
