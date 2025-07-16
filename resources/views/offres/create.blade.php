@extends('layouts.app')

@section('title', 'Créer une Offre')

@section('content')
<h1>Créer une offre</h1>

<form method="POST" action="{{ route('offres.store') }}">
    @csrf
    <div class="mb-3">
        <label for="entreprise_id" class="form-label">Entreprise</label>
        <select name="entreprise_id" id="entreprise_id" class="form-select @error('entreprise_id') is-invalid @enderror" required>
            <option value="">-- Choisir une entreprise --</option>
            @foreach($entreprises as $entreprise)
                <option value="{{ $entreprise->id }}" {{ old('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                    {{ $entreprise->nom }}
                </option>
            @endforeach
        </select>
        @error('entreprise_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror"
               value="{{ old('titre') }}" required>
        @error('titre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" rows="5"
                  class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row mb-3">
        <div class="col">
            <label for="date_debut" class="form-label">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror"
                   value="{{ old('date_debut') }}">
            @error('date_debut')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
            <label for="date_fin" class="form-label">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror"
                   value="{{ old('date_fin') }}">
            @error('date_fin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <button class="btn btn-success" type="submit">Enregistrer</button>
    <a href="{{ route('offres.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
