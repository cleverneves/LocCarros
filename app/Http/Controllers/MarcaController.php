<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MarcaRepository;

class MarcaController extends Controller
{
    public function __construct(private MarcaRepository $repository)
    {
    }

    public function index(Request $request)
    {
        if ($request->has('atributos_modelos')) {
            $this->repository->selectAtributosRegistrosRelacionados('modelos:id,' . $request->atributos_modelos);
        } else {
            $this->repository->selectAtributosRegistrosRelacionados('modelos');
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
        $marca = $this->repository->getModel();

        $this->validarRequisicao($request, $marca->rules(), $marca->feedback());

        $imagem_urn = $request->file('imagem')->store('imagens', 'public');

        $marca = $marca->create([
            'nome'   => $request->nome,
            'imagem' => $imagem_urn,
        ]);

        return response()->json($marca, 201);
    }

    public function show($id)
    {
        $marca = $this->repository->getModel()->with('modelos')->find($id);

        if (is_null($marca)) {
            return response()->json(['erro' => 'Recurso pesquisado não existe.'], 404);
        }

        return response()->json($marca, 200);
    }

    public function update(Request $request, int $id)
    {
        $marca = $this->repository->getModel()->find($id);

        if (is_null($marca)) {
            return response()->json(['erro' => 'Não foi possível atualizar. A marca solicitada é inexistente.'], 404);
        }

        $this->validarRequisicao($request, $marca->rules(), $marca->feedback());

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
            $marca->imagem = $request->file('imagem')->store('imagens', 'public');
        }

        $marca->fill($request->except('imagem'));
        $marca->save();

        return response()->json($marca, 200);
    }

    public function destroy(int $id)
    {
        $marca = $this->repository->getModel()->find($id);

        if (is_null($marca)) {
            return response()->json(['erro' => 'Falha ao excluir. A marca solicitada é inexistente.'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();

        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
