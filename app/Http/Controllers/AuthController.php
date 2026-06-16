<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Mostrar formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Simulação de autenticação - implementar com Auth::attempt()
        if ($credentials['email'] && $credentials['password']) {
            session(['user' => [
                'id' => 1,
                'name' => 'Gestor Sistema',
                'email' => $credentials['email'],
                'role' => 'gestor'
            ]]);
            
            return redirect()->route('dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'As credenciais fornecidas estão incorretas.']);
    }

    /**
     * Processar logout
     */
    public function logout(Request $request)
    {
        session()->flush();
        
        return redirect()->route('login.show');
    }
}
