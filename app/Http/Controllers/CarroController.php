<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{
    public function __construct(private CarroRepository $repository)
    {
    }

    public function index(Request $request)
    {
        if ($request->has('atributos_modelo')) {
            $this->repository->selectAtributosRegistrosRelacionados('modelo:id,' . $request->atributos_modelo);
        } else {
            $this->repository->selectAtributosRegistrosRelacionados('modelo');
        }

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
        $carro = $this->repository->getModel();

        $this->validarRequisicao($request, $carro->rules());

        $carro = $carro->create([
            'modelo_id'  => $request->modelo_id,
            'placa'      => $request->placa,
            'disponivel' => $request->disponivel,
            'km'         => $request->km,
        ]);

        return response()->json($carro, 201);
    }

    public function show(int $id)
    {
        $carro = $this->repository->getModel()->with('modelo')->find($id);

        if (is_null($carro)) {
            return response()->json(['erro' => 'O carro pesquisado não existe.'], 404);
        }

        return response()->json($carro, 200);
    }

    public function update(Request $request, int $id)
    {
        $carro = $this->repository->getModel()->find($id);

        if (is_null($carro)) {
            return response()->json(['erro' => 'Não foi possível atualizar. O carro solicitado é inexistente.'], 404);
        }

        $this->validarRequisicao($request, $carro->rules());

        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    public function destroy(int $id)
    {
        $carro = $this->repository->getModel()->find($id);

        if (is_null($carro)) {
            return response()->json(['erro' => 'Falha ao excluir. O carro solicitado é inexistente.'], 404);
        }

        $carro->delete();

        return response()->json(['msg' => 'O carro foi removido com sucesso!'], 200);
    }
}
