<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

class Negocios extends Model
{
    use SoftDeletes;
    use HasFactory;

    public static function boot () {
        parent::boot();
        self::creating(function($model){
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    protected $table = 'negocios';
    protected $fillable = ['nome','uri','logotipo','descricao','telegram',
    'whatsapp','facebook','twitter', 'instagram','linkedin','pais_id','estado_id','cidade_id','cep',
    'logradouro','numero','bairro','funcionamento','complemento','mostrar_imagem_fundo'];

    protected $dates = ['deleted_at'];

    public function getImagemAttribute(){
        return (!empty($this->logotipo) ? (Storage::disk('public')->exists('negocio/'.$this->uuid.'/'.$this->logotipo) ? url('storage/negocio/'.$this->uuid.'/'.$this->logotipo) : url('assets/imgs/sem-foto.png')): url('assets/imgs/sem-foto.png'));
    }

    public function produtos(){
        return $this->hasMany(Produtos::class);
    }

    public function cidade(){
        return $this->hasOne(Cidade::class,'id','cidade_id');
    }

    public function estado(){
        return $this->hasOne(Estado::class,'id','estado_id');
    }

    public function pais(){
        return $this->hasOne(Pais::class,'id','pais_id');
    }

    public function pedidos(){
        return $this->hasMany(Pedido::class,'negocio_id','id');
    }

    public function configuracao(){
        return $this->hasOne(ConfiguracaoNegocio::class,'negocio_id','id');
    }
}
