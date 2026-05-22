<?php

namespace App\Repositories;

use App\Models\Marca;

class MarcaRepository extends AbstractRepository
{
    public function __construct(Marca $marca)
    {
        parent::__construct($marca);
    }
}
