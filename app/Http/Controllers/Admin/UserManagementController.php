<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // Liste de tous les utilisateurs
    public function index()
    {
        $users = User::withCount(['posts', 'comments', 'favorites'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    // Détails d'un utilisateur
    public function show(User $user)
    {
        $user->load(['posts', 'comments', 'favorites']);

        return view('admin.users.show', compact('user'));
    }

    // Bloquer/Débloquer un utilisateur
    public function toggleBlock(User $user)
    {
        // Empêcher de se bloquer soi-même
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous bloquer vous-même !');
        }

        $user->update([
            'blocked' => !$user->blocked
        ]);

        $status = $user->blocked ? 'bloqué' : 'débloqué';
        return back()->with('success', "Utilisateur {$status} avec succès !");
    }

    // Supprimer un utilisateur
    public function destroy(User $user)
    {
        // Empêcher de se supprimer soi-même
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte !');
        }

        // Empêcher de supprimer un admin
        if ($user->isAdmin()) {
            return back()->with('error', 'Impossible de supprimer un administrateur !');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès !');
    }
}
