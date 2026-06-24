<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Controle de Água' }}</title>
    <link rel="stylesheet" href="{{ asset('css/water-system.css') }}">
    <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }
        .app-container {
            display: flex;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <div class="logo-title">💧 Controle de Água</div>
                <div class="logo-sub">Associação Comunitária</div>
            </div>

            @php $currentUser = auth()->user(); @endphp

            @if ($currentUser?->role === 'gestor')
                <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('consumidores.index') }}" class="sidebar-item {{ request()->routeIs('consumidores.*') ? 'active' : '' }}">
                    👥 Consumidores
                </a>
                <a href="{{ route('leituras.index') }}" class="sidebar-item {{ request()->routeIs('leituras.*') ? 'active' : '' }}">
                    📝 Leituras
                </a>
                <a href="{{ route('faturas.index') }}" class="sidebar-item {{ request()->routeIs('faturas.*') ? 'active' : '' }}">
                    💳 Faturas
                </a>
                <a href="{{ route('configuracao.index') }}" class="sidebar-item {{ request()->routeIs('configuracao.*') ? 'active' : '' }}">
                    ⚙️ Configuração
                </a>
            @elseif ($currentUser?->role === 'leiturista')
                <a href="{{ route('leiturista.dashboard') }}" class="sidebar-item {{ request()->routeIs('leiturista.dashboard') ? 'active' : '' }}">
                    🧭 Leiturista
                </a>
                <a href="{{ route('leituras.index') }}" class="sidebar-item {{ request()->routeIs('leituras.*') ? 'active' : '' }}">
                    📝 Leituras
                </a>
            @endif

            <div style="padding: 1rem 1rem; margin-top: auto; border-top: 0.5px solid rgba(255,255,255,0.12);">
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-ghost" style="color: rgba(255,255,255,0.65); width: 100%; text-align: left; padding: 0;">
                        🚪 Sair
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Area -->
        <div class="main-area">
            <div class="topbar">
                <div class="topbar-title">{{ $title ?? 'Dashboard' }}</div>
                <div class="topbar-user">
                    <div class="avatar">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</div>
                    <span>{{ auth()->user()->name ?? 'Usuário' }}</span>
                </div>
            </div>

            <div class="content">
                @if (session('success'))
                    <div class="alert-box alert-success">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert-box alert-warn">
                        ⚠️ {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/water-system.js') }}"></script>
    @stack('scripts')
</body>
</html>
