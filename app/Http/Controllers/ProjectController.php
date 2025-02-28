<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // retorna os projetos do usuario logado
    public function index()
    {
        $projects = Project::where('user_id', Auth::id())->with('tasks')->get();

        return response()->json($projects);
    }

    // cria um novo projeto
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json($project, 201);
    }

    // exibe um projeto
    public function show($id)
    {
        $project = Project::where('id', $id)->where('user_id', Auth::id())->with('tasks')->firstOrFail();

        return response()->json($project);
    }

    // atualiza um projeto existente
    public function update(Request $request, $id)
    {
        $project = Project::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($request->only([
            'name', 'description'
        ]));

        return response()->json($project);
    }

    // exclui um projeto e sua tarefas
    public function destroy($id)
    {
        $project = Project::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $project->delete();

        return response()->json(['message' => 'Project excluído com sucesso!']);
    }
}
