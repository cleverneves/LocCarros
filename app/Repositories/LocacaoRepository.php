<?php

namespace App\Repositories;

use App\Models\Locacao;

class LocacaoRepository extends AbstractRepository
{
    public function __construct(Locacao $locacao)
    {
        parent::__construct($locacao);
    }
}
