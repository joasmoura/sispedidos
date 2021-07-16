<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $fillable = ['pedido','negocio_id','user_id','observacoes','status'];

    public function negocio(){
        return $this->hasOne(Negocios::class,'id','negocio_id');
    }

    public function itens(){
        return $this->hasMany(PedidoItens::class,'pedido_id','id');
    }

    public function statusPedido(){
        return $this->hasMany(StatusPedidos::class,'pedido_id','id');
    }
}
