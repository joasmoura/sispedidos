<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPedidos extends Model
{
    use HasFactory;
    protected $table = 'status_pedidos';
    protected $fillable = ['pedido_id','status','user_id','obs'];

    public function pedido(){
        return $this->hasOne(Pedido::class,'id','pedido_id');
    }

    public function usuario(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
