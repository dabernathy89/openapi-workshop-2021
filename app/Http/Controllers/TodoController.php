<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTodo;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Todo::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTodo $request)
    {
        $todo = Todo::create([
            'title' => $request->input('title'),
            'completed' => $request->input('completed'),
        ]);

        return response()->json($todo, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $todo->update([
            'title' => $request->input('title', $todo->title),
            'completed' => $request->input('completed', $todo->completed),
        ]);

        return response()->json($todo->fresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json(null, 204);
    }
}
