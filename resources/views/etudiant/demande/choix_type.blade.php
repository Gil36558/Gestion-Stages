@extends('layouts.app')

@section('title', 'Type de stage')

@push('styles')
    <style>
        /* Variables CSS personnalisées alignées avec les précédents */
        :root {
            --primary-blue: #2563eb;
            --primary-dark: #1e40af;
            --secondary-blue: #eff6ff;
            --accent-blue: #3b82f6;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --bg-light: #f8fafc;
            --border-light: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --gradient-primary: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
        }

        body {
            background: var(--secondary-blue);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .main-container {
            padding: 2.5rem 1.5rem;
            margin: 2rem auto;
            max-width: 36rem;
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-card {
            background: white;
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .title-container {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .title-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 3.5rem;
            background: var(--gradient-primary);
            border-radius: 50%;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .title-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            color: white;
        }

        .main-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .form-select {
            appearance: none;
            width: 100%;
            padding: 0.875rem 2.5rem 0.875rem 1rem;
            border: 1px solid var(--border-light);
            border-radius: 0.75rem;
            font-size: 1rem;
            color: var(--text-primary);
            background: white;
            transition: all 0.3s ease;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }

        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .form-select option {
            padding: 0.5rem;
            font-size: 1rem;
            color: var(--text-primary);
        }

        .form-select option:first-child {
            color: var(--text-light);
        }

        .submit-btn {
            width: auto;
            padding: 0.5rem 1.25rem;
            background: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 640px) {
            .main-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .main-title {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 0.875rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center p-4" data-aos="fade-up" data-aos-duration="800">
    <div class="main-container">
        <div class="form-card" data-aos="zoom-in" data-aos-delay="100">
            <!-- Titre avec icône -->
            <div class="title-container">
                <div class="title-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h1 class="main-title">Demande de stage</h1>
                <p class="subtitle">Choisissez le type de stage qui vous convient</p>
            </div>

            <form action="{{ route('demande.stage.form') }}" method="GET" class="space-y-6" data-aos="fade-up" data-aos-delay="200">
                <input type="hidden" name="entreprise_id" value="{{ request('entreprise_id') }}">

                <div class="form-group">
                    <label for="type" class="form-label">Type de stage souhaité</label>
                    <select name="type" id="type" required class="form-select">
                        <option value="">Sélectionnez votre type de stage</option>
                        <option value="academique">🎓 Stage académique</option>
                        <option value="professionnel">💼 Stage professionnel</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn">
                    Continuer
                    <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 100,
            duration: 800,
            easing: 'ease-out-cubic',
        });

        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('type');
            const submitBtn = document.querySelector('.submit-btn');

            select.addEventListener('change', function() {
                if (this.value !== '') {
                    this.style.borderColor = var(--primary-blue);
                    this.style.boxShadow = '0 0 0 3px rgba(37, 99, 235, 0.1)';
                    submitBtn.style.background = var(--primary-dark);
                } else {
                    this.style.borderColor = var(--border-light);
                    this.style.boxShadow = 'none';
                    submitBtn.style.background = var(--primary-blue);
                }
            });

            select.addEventListener('mouseenter', function() {
                if (this.value === '') {
                    this.style.borderColor = var(--accent-blue);
                }
            });

            select.addEventListener('mouseleave', function() {
                if (this.value === '') {
                    this.style.borderColor = var(--border-light);
                }
            });
        });
    </script>
@endpush