@extends('layouts.auth')

@section('title', 'Inscription · KerpheX')
@section('subtitle', 'Inscription réservée aux adresses Gmail (@gmail.com).')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="auth-form" autocomplete="off">
        @csrf
        <div class="form-group">
            <label class="field-label">Nom</label>
            <input type="text" name="name" class="form-input" value="{{ old('name') }}" autocomplete="off" autocapitalize="words" readonly onfocus="this.removeAttribute('readonly')" required autofocus>
            @error('name') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label class="field-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="votre@gmail.com" autocomplete="off" autocapitalize="off" spellcheck="false" readonly onfocus="this.removeAttribute('readonly')" required>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label class="field-label">Mot de passe</label>
            <input type="password" name="password" class="form-input" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')" required>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label class="field-label">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="form-input" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')" required>
        </div>
        <button type="submit" class="btn-primary btn-accent" style="width:100%;justify-content:center;">S'inscrire</button>
    </form>
@endsection

@section('footer')
    <p>Déjà inscrit ? <a href="{{ route('login') }}" class="auth-link">Se connecter</a></p>
@endsection
