<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{
    public function __construct(private LocacaoRepository $repository)
    {
    }

    public function index(Request $request)
    {
        if ($request->has('filtro')) {
            $this->repository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $this->repository->selectAtributos($request->atributos);
        }

        return response()->json($this->repository->getResultado(), 200);
    }

    public function store(Request $request)
    {
        $locacao = $this->repository->getModel();

        $this->validarRequisicao($request, $locacao->rules());

        $locacao = $locacao->create([
            'cliente_id'                  => $request->cliente_id,
            'carro_id'                    => $request->carro_id,
            'data_inicio_periodo'         => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo'=> $request->data_final_realizado_periodo,
            'valor_diaria'                => $request->valor_diaria,
            'km_inicial'                  => $request->km_inicial,
            'km_final'                    => $request->km_final,
        ]);

        return response()->json($locacao, 201);
    }

    public function show(int $id)
    {
        $locacao = $this->repository->getModel()->find($id);

        if (is_null($locacao)) {
            return response()->json(['erro' => 'Locação não encontrada.'], 404);
        }

        return response()->json($locacao, 200);
    }

    public function update(Request $request, int $id)
    {
        $locacao = $this->repository->getModel()->find($id);

        if (is_null($locacao)) {
            return response()->json(['erro' => 'Não foi possível atualizar. A locação solicitada é inexistente.'], 404);
        }

        $this->validarRequisicao($request, $locacao->rules());

        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    public function destroy(int $id)
    {
        $locacao = $this->repository->getModel()->find($id);

        if (is_null($locacao)) {
            return response()->json(['erro' => 'Falha ao excluir. A locação solicitada é inexistente.'], 404);
        }

        $locacao->delete();

        return response()->json(['msg' => 'A locação foi removida com sucesso!'], 200);
    }
}
