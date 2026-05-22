<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{
    public function __construct(private ClienteRepository $repository)
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
        $cliente = $this->repository->getModel();

        $this->validarRequisicao($request, $cliente->rules());

        $cliente = $cliente->create([
            'nome' => $request->nome,
        ]);

        return response()->json($cliente, 201);
    }

    public function show(int $id)
    {
        $cliente = $this->repository->getModel()->find($id);

        if (is_null($cliente)) {
            return response()->json(['erro' => 'O cliente pesquisado não existe.'], 404);
        }

        return response()->json($cliente, 200);
    }

    public function update(Request $request, int $id)
    {
        $cliente = $this->repository->getModel()->find($id);

        if (is_null($cliente)) {
            return response()->json(['erro' => 'Não foi possível atualizar. O cliente solicitado é inexistente.'], 404);
        }

        $this->validarRequisicao($request, $cliente->rules());

        $cliente->fill($request->all());
        $cliente->save();

        return response()->json($cliente, 200);
    }

    public function destroy(int $id)
    {
        $cliente = $this->repository->getModel()->find($id);

        if (is_null($cliente)) {
            return response()->json(['erro' => 'Impossível excluir. Cliente inexistente.'], 404);
        }

        $cliente->delete();

        return response()->json(['msg' => 'O cliente foi removido com sucesso!'], 200);
    }
}
