<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Temporariamente desabilitado para teste
        // $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        // Temporariamente redirecionando direto para início
        return $this->inicio();
    }

    public function inicio()
    {
        // Temporariamente sem banco de dados
        $files = collect(); // Array vazio para teste
        return view('dashboard.inicio', compact('files'));
    }

    public function arquivos()
    {
        return view('dashboard.arquivos');
    }

    public function processados()
    {
        return view('dashboard.processados');
    }

    public function guia()
    {
        return view('dashboard.guia');
    }

    public function auditoria()
    {
        return view('dashboard.auditoria');
    }

    public function equipe()
    {
        return view('dashboard.equipe');
    }

    public function painelAdm()
    {
        return view('dashboard.painel-adm');
    }

    public function meusDados()
    {
        return view('dashboard.meus-dados');
    }
}
