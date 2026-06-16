@extends('layouts.auth')

@section('title', 'Mot de passe oublié · KerpheX')
@section('subtitle', 'Entrez votre email pour recevoir un lien de réinitialisation.')

@section('content')
    <form method="POST" action="{{ route('password.email') }}" class="auth-form" autocomplete="off">
        @csrf
        <div class="form-group">
            <label class="field-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" autocomplete="off" autocapitalize="off" spellcheck="false" readonly onfocus="this.removeAttribute('readonly')" required autofocus>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-primary btn-accent" style="width:100%;justify-content:center;">Envoyer le lien</button>
    </form>
@endsection

@section('footer')
    <p><a href="{{ route('login') }}" class="auth-link">← Retour à la connexion</a></p>
@endsection
