@extends('layouts.auth')

@section('title', 'Confirmer votre compte · KerpheX')
@section('subtitle', 'Validez la création de votre compte avec le code reçu sur '.$email)

@section('content')
    <form method="POST" action="{{ route('register.verify.submit') }}" class="auth-form">
        @csrf
        <div class="form-group">
            <label class="field-label">Code de confirmation (OTP)</label>
            <input
                type="text"
                name="otp"
                class="form-input"
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                placeholder="123456"
                value="{{ old('otp') }}"
                required
                autofocus
                autocomplete="one-time-code"
            >
            @error('otp') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <p style="font-size:0.85rem;color:var(--ink-muted);margin-bottom:1rem;">
            Consultez la boîte Gmail <strong>{{ $email }}</strong> (et le dossier Spam).
        </p>
        <button type="submit" class="btn-primary btn-accent" style="width:100%;justify-content:center;">Valider mon compte</button>
    </form>

    <form method="POST" action="{{ route('register.verify.resend') }}" style="margin-top:1rem;text-align:center;">
        @csrf
        <button type="submit" class="auth-link" style="background:none;border:none;cursor:pointer;padding:0;font:inherit;">
            Renvoyer le code
        </button>
    </form>
@endsection

@section('footer')
    <p><a href="{{ route('register') }}" class="auth-link">← Modifier mes informations</a></p>
@endsection
