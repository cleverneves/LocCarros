<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function validarRequisicao(Request $request, array $regras, array $feedback = []): void
    {
        if ($request->isMethod('PATCH')) {
            $regras = array_intersect_key($regras, $request->all());
        }

        $request->validate($regras, $feedback);
    }
}
