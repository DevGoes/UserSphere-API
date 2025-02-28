<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{

    // Retorna todas as tarefas de um projeto do usuário autenticado.
    public function index($projectId)
    {
        $project = Project::where('id', $projectId)->where('user_id', Auth::id())->firstOrFail();
        return response()->json($project->tasks);
    }

    // Armazena uma nova tarefa em um projeto.
    public function store(Request $request, $projectId)
    {
        $project = Project::where('id', $projectId)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Pendente,Em andamento,Concluído',
        ]);

        $task = $project->tasks()->create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json($task, 201);
    }

    // Exibe uma tarefa específica dentro de um projeto do usuário autenticado.
    public function show($projectId, $taskId)
    {
        $task = Task::where('id', $taskId)
            ->whereHas('project', function ($query) use ($projectId) {
                $query->where('id', $projectId)->where('user_id', Auth::id());
            })->firstOrFail();

        return response()->json($task);
    }

    // Atualiza uma tarefa existente.
    public function update(Request $request, $projectId, $taskId)
    {
        $task = Task::where('id', $taskId)
            ->whereHas('project', function ($query) use ($projectId) {
                $query->where('id', $projectId)->where('user_id', Auth::id());
            })->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Pendente,Em andamento,Concluído',
        ]);

        $task->update($request->only(['name', 'description', 'status']));

        return response()->json($task);
    }

    //Exclui uma tarefa dentro de um projeto.
    public function destroy($projectId, $taskId)
    {
        $task = Task::where('id', $taskId)
            ->whereHas('project', function ($query) use ($projectId) {
                $query->where('id', $projectId)->where('user_id', Auth::id());
            })->firstOrFail();

        $task->delete();

        return response()->json(['message' => 'Tarefa excluída com sucesso']);
    }
}
