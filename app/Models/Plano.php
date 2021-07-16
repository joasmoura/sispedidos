<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;
    protected $table = 'planos';
    protected $fillable = ['nome', 'nome_pagarme','codigo','dias_cobranca','avisos_antes','descricao','valor','total_registros','periodo_testes','status'];
}
