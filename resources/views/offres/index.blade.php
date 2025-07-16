@extends('layouts.app')

@section('title', 'Liste des Offres')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Offres de stage</h1>
    <a href="{{ route('offres.create') }}" class="btn btn-primary">Ajouter une offre</a>
</div>

@if($offres->isEmpty())
    <p>Aucune offre pour le moment.</p>
@else
<table class="table table-striped">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Entreprise</th>
            <th>Date début</th>
            <th>Date fin</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($offres as $offre)
            <tr>
                <td>{{ $offre->titre }}</td>
                <td>{{ $offre->entreprise->nom }}</td>
                <td>{{ $offre->date_debut ? \Illuminate\Support\Carbon::parse($offre->date_debut)->format('d/m/Y') : '-' }}</td>
                <td>{{ $offre->date_fin ? \Illuminate\Support\Carbon::parse($offre->date_fin)->format('d/m/Y') : '-' }}</td>
                <td>
                    <a href="{{ route('offres.edit', $offre) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('offres.destroy', $offre) }}" method="POST" class="d-inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger btn-delete">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Confirmer la suppression ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
