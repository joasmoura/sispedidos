<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItens extends Model
{
    use HasFactory;
    protected $table = 'pedido_itens';
    protected $fillable = ['pedido_id','produto_id','opcao_id','qtd','valor'];

    public function produto(){
        return $this->hasOne(Produtos::class,'id','produto_id');
    }

    public function opcoes(){
        return $this->hasMany(PedidoItens::class,'opcao_id','id');
    }
}
