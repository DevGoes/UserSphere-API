<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    // listar um usuário
    public function show(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json($user);
    }

    // atualizar dados do usuário
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'password' => 'sometimes|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('phone')) $user->phone = $request->phone;
        if ($request->has('password')) $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Usuário atualizado com sucesso!', 'user' => $user]);
    }

    // deletar um usuário
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Usuário deletado com sucesso!']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['error' => 'Nenhum termo de busca fornecido'], 400);
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->get(); // Mudamos para get() para retornar uma lista

        if ($users->isEmpty()) {
            return response()->json(['error' => 'Nenhum usuário encontrado'], 404);
        }

        return response()->json($users);
    }

}
