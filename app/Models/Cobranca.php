<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobranca extends Model
{
    use HasFactory;
    protected $table = 'cobrancas';
    protected $fillable = ['transacao_id','assinatura_id','transacao_id','metodo_pagamento','valor','codigo_boleto','vencimento','status','boleto_url','created_at','updated_at'];
}
