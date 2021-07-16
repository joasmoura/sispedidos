<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    use HasFactory;
    protected $table = 'assinaturas';
    protected $fillable = ['user_id','plano_id','max_registros','codigo_assinatura','nome_plano','valor','metodo_pagamento','status','dados_assinatura','periodo_testes'];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function cobrancas(){
        return $this->hasMany(Cobranca::class,'assinatura_id','id');
    }

    public function plano(){
        return $this->hasOne(Plano::class,'id','plano_id');
    }
}
