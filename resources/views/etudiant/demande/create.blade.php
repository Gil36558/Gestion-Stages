@extends('layouts.app')
@section('title', 'Faire une demande académique')

@section('content')
<div class="container mt-5">
    <h3>📝 Nouvelle demande de stage académique</h3>

    <form action="{{ route('demande.store') }}" method="POST">
        @csrf

        <input type="hidden" name="type" value="academique">

        <div class="mb-3">
            <label for="objet" class="form-label">Objet du stage</label>
            <input type="text" name="objet" id="objet" class="form-control" required>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="periode_debut" class="form-label">Début</label>
                <input type="date" name="periode_debut" id="periode_debut" class="form-control" required>
            </div>
            <div class="col">
                <label for="periode_fin" class="form-label">Fin</label>
                <input type="date" name="periode_fin" id="periode_fin" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="membres" class="form-label">Binôme/Trinôme (facultatif)</label>
            <select name="membres[]" id="membres" class="form-select" multiple>
                @foreach($etudiants as $etudiant)
                    <option value="{{ $etudiant->id }}">{{ $etudiant->name }} ({{ $etudiant->email }})</option>
                @endforeach
            </select>
            <small class="text-muted">Vous pouvez choisir jusqu'à 2 membres max.</small>
        </div>

        <button type="submit" class="btn btn-primary">Envoyer la demande</button>
    </form>
</div>
@endsection
