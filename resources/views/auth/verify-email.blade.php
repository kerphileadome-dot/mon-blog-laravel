@extends('layouts.auth')

@section('title', 'Vérification email · KerpheX')
@section('subtitle', 'Vérifiez votre adresse email pour continuer.')

@section('content')
    <p style="color:var(--ink-muted);font-size:0.9rem;margin-bottom:1.5rem;line-height:1.6;">
        Un lien de vérification a été envoyé à votre adresse email. Cliquez sur le lien pour activer votre compte.
    </p>
    @if (session('status') == 'verification-link-sent')
        <div class="flash-msg" style="margin-bottom:1rem;">Un nouveau lien a été envoyé.</div>
    @endif
    <form method="POST" action="{{ route('verification.send') }}" class="auth-form">
        @csrf
        <button type="submit" class="btn-primary btn-accent" style="width:100%;justify-content:center;">Renvoyer le lien</button>
    </form>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:1rem;">
        @csrf
        <button type="submit" class="btn-ghost" style="width:100%;justify-content:center;">Se déconnecter</button>
    </form>
@endsection
