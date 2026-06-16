<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Água - Login</title>
    <link rel="stylesheet" href="{{ asset('css/water-system.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #0A3D62 0%, #1A5276 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <div class="drop">💧</div>
                <h1>Controle de Água</h1>
                <p>Associação Comunitária</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">E-mail</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="seu@email.com"
                        required
                    >
                    @error('email')
                        <span style="color: #C0392B; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Senha</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Sua senha"
                        required
                    >
                    @error('password')
                        <span style="color: #C0392B; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                    Entrar
                </button>
            </form>

            @if ($errors->any())
                <div class="alert-box alert-warn" style="margin-top: 1rem;">
                    ⚠️
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
