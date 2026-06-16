@extends('layouts.auth')

@section('title', 'Nouveau mot de passe · KerpheX')
@section('subtitle', 'Modifiez votre mot de passe puis validez pour retrouver l\'accès à votre espace.')

@section('content')
    <form method="POST" action="{{ route('password.store') }}" class="auth-form" autocomplete="off">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="form-group">
            <label class="field-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $request->email) }}" autocomplete="off" required autofocus readonly>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label class="field-label">Modifier votre mot de passe</label>
            <input type="password" name="password" class="form-input" placeholder="Nouveau mot de passe" autocomplete="new-password" required>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label class="field-label">Confirmer le nouveau mot de passe</label>
            <input type="password" name="password_confirmation" class="form-input" placeholder="Confirmer le mot de passe" autocomplete="new-password" required>
        </div>
        <button type="submit" class="btn-primary btn-accent" style="width:100%;justify-content:center;">Valider</button>
    </form>
@endsection

@section('footer')
    <p><a href="{{ route('login') }}" class="auth-link">← Retour à la connexion</a></p>
@endsection
