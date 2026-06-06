@extends('layouts.app')

@section('title', 'Mon profil · KerpheX')

@section('content')
<div class="page-header">
    <h1 class="page-title">Mon profil</h1>
    <p class="page-desc">Gérez vos informations personnelles et votre mot de passe.</p>
</div>

<div style="max-width:640px;margin:0 auto;">
    <div class="write-form" style="margin-bottom:2rem;">
        <h2 class="write-title" style="font-size:1.5rem;margin-bottom:1.5rem;">Informations</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="auth-form">
            @csrf
            @method('patch')
            <div class="form-group">
                <label class="field-label">Nom</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="field-label">Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-primary btn-accent">Enregistrer</button>
        </form>
    </div>

    <div class="write-form" style="margin-bottom:2rem;">
        <h2 class="write-title" style="font-size:1.5rem;margin-bottom:1.5rem;">Mot de passe</h2>
        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
            @csrf
            @method('put')
            <div class="form-group">
                <label class="field-label">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-input" required>
                @error('current_password', 'updatePassword') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="field-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-input" required>
                @error('password', 'updatePassword') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="field-label">Confirmer</label>
                <input type="password" name="password_confirmation" class="form-input" required>
            </div>
            <button type="submit" class="btn-primary btn-accent">Mettre à jour le mot de passe</button>
        </form>
    </div>

    <div class="write-form" style="border-color:rgba(220,38,38,0.3);">
        <h2 class="write-title" style="font-size:1.5rem;margin-bottom:1rem;color:#dc2626;">Supprimer le compte</h2>
        <p style="color:var(--ink-muted);font-size:0.9rem;margin-bottom:1.5rem;">Cette action est irréversible.</p>
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Supprimer définitivement votre compte ?')">
            @csrf
            @method('delete')
            <div class="form-group">
                <label class="field-label">Mot de passe</label>
                <input type="password" name="password" class="form-input" required>
                @error('password', 'userDeletion') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-ghost" style="border-color:#dc2626;color:#dc2626;">Supprimer mon compte</button>
        </form>
    </div>
</div>
@endsection
