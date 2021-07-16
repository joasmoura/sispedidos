<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produtos extends Model
{
    use HasFactory;

    protected $table = 'produtos';
    protected $fillable = ['nome','descricao','valor','logotipo','negocios_id','produtos_id','opcao_id','uri', 'indisponivel','mostrar','max_opcoes','max_opcoes_pagas'];

    public function getImagemAttribute(){
        return (!empty($this->logotipo) ? (Storage::disk('public')->exists('negocio/'.$this->negocio->uuid.'/'.$this->logotipo) ? url('storage/negocio/'.$this->negocio->uuid.'/'.$this->logotipo) : url('assets/imgs/sem-foto.jpg')): url('assets/imgs/sem-foto.jpg'));
    }

    public function getImagemItemAttribute(){
        $produto = $this->produto;
        return (!empty($this->logotipo) ? (Storage::disk('public')->exists('negocio/'.$produto->negocio->uuid.'/'.$this->logotipo) ? url('storage/negocio/'.$produto->negocio->uuid.'/'.$this->logotipo) : url('assets/imgs/sem-foto.jpg')): url('assets/imgs/sem-foto.jpg'));
    }

    public function negocio(){
        return $this->hasOne(Negocios::class,'id','negocios_id');
    }

    public function produto(){
        return $this->hasOne(Produtos::class,'id','produtos_id');
    }

    public function itens(){
        return $this->hasMany(Produtos::class,'produtos_id','id');
    }

    public function opcoes(){
        return $this->hasMany(Produtos::class,'opcao_id','id');
    }
}
