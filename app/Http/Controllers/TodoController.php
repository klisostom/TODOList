<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;
use Illuminate\Support\Facades\Log;
use App\Repositories\Todos\TodoRepository;

class TodoController extends Controller
{
    protected $repository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->repository = $todoRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $message = $this->repository->all();

        return isset($message) ? response()
            ->json($message, 200) : response()
            ->json(['Sem tarefas'], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TodoRequest $request)
    {
        $message = $this->repository->create($request->all());

        return isset($message) ? response()
            ->json($message, 200) : response()
            ->json(['Não foi possível salvar os dados.'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $message = $this->repository->update($request->all(), $id);

        return isset($message) ? response()
            ->json($message, 200) : response()
            ->json(['Não foi possível atualizar os dados.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = $this->repository->delete($id);

        return ($message) ? response('', 204) : response()->json('Todo não existe.', 404);
    }
}
