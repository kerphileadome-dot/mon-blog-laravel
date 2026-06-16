@extends('layouts.auth')

@section('title', 'Administration · KerpheX')
@section('subtitle', 'Accès réservé aux administrateurs du blog.')

@section('content')
    @if(session('error'))
        <div class="flash-msg flash-error" style="margin-bottom:1rem;">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.login.submit') }}" class="auth-form" autocomplete="off">
        @csrf
        <div class="form-group">
            <label class="field-label">Email administrateur</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" autocomplete="off" autocapitalize="off" spellcheck="false" readonly onfocus="this.removeAttribute('readonly')" required autofocus>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label class="field-label">Mot de passe</label>
            <input type="password" name="password" class="form-input" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')" required>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <label class="publish-checkbox" style="margin-bottom:1.25rem;">
            <input type="checkbox" name="remember" style="accent-color:var(--accent-bright);"> Se souvenir
        </label>
        <button type="submit" class="btn-primary btn-accent" style="width:100%;justify-content:center;">Accéder au panneau admin</button>
    </form>
@endsection

@section('footer')
    <p><a href="{{ route('posts.index') }}" class="auth-link">← Retour au blog</a></p>
    <p style="margin-top:0.5rem;"><a href="{{ route('login') }}" class="auth-link-muted">Connexion visiteur</a></p>
@endsection
