@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tableau de bord</h4>
                </div>
                <div class="card-body">
                    <p class="lead mb-0">
                        👋 Bienvenue, {{ Auth::user()->name }} !
                    </p>
                    <hr>
                    <p>
                        Vous êtes connecté en tant que <strong>{{ ucfirst(Auth::user()->role) }}</strong>.
                    </p>

                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="btn btn-outline-danger mt-3">
                        Se déconnecter
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
