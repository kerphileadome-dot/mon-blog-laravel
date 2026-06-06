@extends('layouts.auth')

@section('title', 'Nouveau mot de passe · KerpheX')
@section('subtitle', 'Choisissez un nouveau mot de passe sécurisé.')

@section('content')
    <form method="POST" action="{{ route('password.store') }}" class="auth-form">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="form-group">
            <label class="field-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $request->email) }}" required autofocus>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label class="field-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-input" required>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label class="field-label">Confirmer</label>
            <input type="password" name="password_confirmation" class="form-input" required>
        </div>
        <button type="submit" class="btn-primary btn-accent" style="width:100%;justify-content:center;">Réinitialiser</button>
    </form>
@endsection
