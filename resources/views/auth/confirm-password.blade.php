@extends('layouts.auth')

@section('title', 'Confirmation · KerpheX')
@section('subtitle', 'Confirmez votre mot de passe pour accéder à cette zone sécurisée.')

@section('content')
    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf
        <div class="form-group">
            <label class="field-label">Mot de passe</label>
            <input type="password" name="password" class="form-input" required autofocus>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-primary btn-accent" style="width:100%;justify-content:center;">Confirmer</button>
    </form>
@endsection
