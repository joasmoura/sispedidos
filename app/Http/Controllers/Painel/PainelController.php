<?php

namespace App\Http\Controllers\Painel;

use App\Events\AssinaturaEvent;
use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Models\Pais;
use Illuminate\Http\Request;

class PainelController extends Controller
{
    public function index(){
        event(new AssinaturaEvent());
        return view('Painel.index');
    }

    public function perfil(){
        return view('Painel.perfil');
    }

    public function estados($id, Pais $paises){
        $pais = $paises->find($id);
        if($pais){
            $estados = $pais->estados;
            return compact('estados');
        }
    }

    public function cidades($id, Estado $estados){
        $estado = $estados->find($id);
        if($estado){
            $cidades = $estado->cidades;
            return compact('cidades');
        }
    }
}
