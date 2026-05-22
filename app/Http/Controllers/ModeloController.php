<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ModeloRepository;

class ModeloController extends Controller
{
    public function __construct(private ModeloRepository $repository)
    {
    }

    public function index(Request $request)
    {
        if ($request->has('atributos_marca')) {
            $this->repository->selectAtributosRegistrosRelacionados('marca:id,' . $request->atributos_marca);
        } else {
            $this->repository->selectAtributosRegistrosRelacionados('marca');
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
        $modelo = $this->repository->getModel();

        $this->validarRequisicao($request, $modelo->rules());

        $imagem_urn = $request->file('imagem')->store('imagens/modelos', 'public');

        $modelo = $modelo->create([
            'marca_id'      => $request->marca_id,
            'nome'          => $request->nome,
            'imagem'        => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares'       => $request->lugares,
            'air_bag'       => $request->air_bag,
            'abs'           => $request->abs,
        ]);

        return response()->json($modelo, 201);
    }

    public function show(int $id)
    {
        $modelo = $this->repository->getModel()->with('marca')->find($id);

        if (is_null($modelo)) {
            return response()->json(['erro' => 'Recurso pesquisado não existe.'], 404);
        }

        return response()->json($modelo, 200);
    }

    public function update(Request $request, int $id)
    {
        $modelo = $this->repository->getModel()->find($id);

        if (is_null($modelo)) {
            return response()->json(['erro' => 'Não foi possível atualizar. O modelo solicitado é inexistente.'], 404);
        }

        $this->validarRequisicao($request, $modelo->rules());

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
            $modelo->imagem = $request->file('imagem')->store('imagens/modelos', 'public');
        }

        $modelo->fill($request->except('imagem'));
        $modelo->save();

        return response()->json($modelo, 200);
    }

    public function destroy(int $id)
    {
        $modelo = $this->repository->getModel()->find($id);

        if (is_null($modelo)) {
            return response()->json(['erro' => 'Falha ao excluir. O modelo solicitado é inexistente.'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();

        return response()->json(['msg' => 'O modelo foi removido com sucesso!'], 200);
    }
}
