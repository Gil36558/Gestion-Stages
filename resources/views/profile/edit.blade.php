@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Modifier mon profil</h2>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom', $user->nom) }}" required>
            </div>
            <div class="col-md-6">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="date_naissance" class="form-label">Date de naissance</label>
                <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $user->date_naissance) }}">
            </div>
            <div class="col-md-6">
                <label for="matricule" class="form-label">Numéro matricule</label>
                <input type="text" name="matricule" class="form-control" value="{{ old('matricule', $user->matricule) }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="filiere" class="form-label">Filière</label>
                <input type="text" name="filiere" class="form-control" value="{{ old('filiere', $user->filiere) }}">
            </div>
            <div class="col-md-6">
                <label for="ecole" class="form-label">Ecole</label>
                <input type="text" name="ecole" class="form-control" value="{{ old('ecole', $user->ecole) }}">
            </div>
        </div>

        <hr>
        <h5 class="mb-3">Changer le mot de passe</h5>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
