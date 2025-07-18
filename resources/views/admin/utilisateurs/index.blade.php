@extends('layouts.app')

@section('title', 'Administration - Utilisateurs')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">👥 Gestion des Utilisateurs</h1>
                    <p class="text-gray-600">Gérer tous les comptes utilisateurs du système</p>
                </div>
                <a href="{{ route('admin.utilisateurs.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    ➕ Nouvel utilisateur
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Étudiants</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['etudiants']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Entreprises</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['entreprises']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Admins</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['admins']) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <form method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher par nom ou email..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tous les rôles</option>
                        <option value="etudiant" {{ request('role') === 'etudiant' ? 'selected' : '' }}>Étudiants</option>
                        <option value="entreprise" {{ request('role') === 'entreprise' ? 'selected' : '' }}>Entreprises</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admins</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    🔍 Filtrer
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.utilisateurs') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        ✖️ Effacer
                    </a>
                @endif
            </form>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Informations</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscription</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($utilisateurs as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mr-4">
                                            <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($user->role === 'etudiant') bg-blue-100 text-blue-800
                                        @elseif($user->role === 'entreprise') bg-green-100 text-green-800
                                        @elseif($user->role === 'admin') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($user->role === 'etudiant') 🎓 Étudiant
                                        @elseif($user->role === 'entreprise') 🏢 Entreprise
                                        @elseif($user->role === 'admin') 👑 Admin
                                        @else {{ ucfirst($user->role) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($user->role === 'entreprise' && $user->entreprise)
                                        <div>{{ $user->entreprise->nom }}</div>
                                        <div class="text-xs">{{ $user->entreprise->secteur ?? 'Secteur non spécifié' }}</div>
                                    @elseif($user->role === 'etudiant')
                                        <div>{{ $user->filiere ?? 'Filière non spécifiée' }}</div>
                                        <div class="text-xs">{{ $user->ecole ?? 'École non spécifiée' }}</div>
                                    @else
                                        <div>{{ $user->telephone ?? 'Téléphone non renseigné' }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs">{{ $user->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.utilisateurs.edit', $user) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            ✏️ Modifier
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.utilisateurs.destroy', $user) }}" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    🗑️ Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                        </svg>
                                        <p class="text-lg font-medium">Aucun utilisateur trouvé</p>
                                        <p class="text-sm">Essayez de modifier vos critères de recherche</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($utilisateurs->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $utilisateurs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
