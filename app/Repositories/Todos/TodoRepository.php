<?php

namespace App\Repositories\Todos;

use App\Models\Todo;
use App\Repositories\BaseRepository;

class TodoRepository extends BaseRepository
{

    // Constructor to bind model to repo
    public function __construct(Todo $todos)
    {
        parent::__construct($todos);
    }

    public function all()
    {
        return Todo::all();
    }

    public function show(int $todoId)
    {
        $todoExist = Todo::find($todoId);

        return isset($todoExist) ? parent::show($todoId) : false;
    }

    public function delete(int $todoId)
    {
        $todoExist = Todo::find($todoId);

        return isset($todoExist) ? parent::delete($todoId) : false;
    }

    public function create(array $dados)
    {
        return parent::create($dados);
    }

    public function update(array $data, int $id)
    {
        $todoExist = Todo::find($id);
        $todoExist->todo = $data['todo'];
        $todoExist->save();

        return $todoExist;
    }
}
