<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ConfiguracaoNegocio extends Model
{
    use HasFactory;
    protected $table ='configuracao_negocios';
    protected $fillable = ['negocio_id','img_fundo','bg_fundo','modelo_lista'];

    public function getImagemAttribute(){
        return (!empty($this->img_fundo) ? (Storage::disk('public')->exists('negocio/'.$this->negocio->uuid.'/'.$this->img_fundo) ? url('storage/negocio/'.$this->negocio->uuid.'/'.$this->img_fundo) : ''): '');
    }

    public function negocio(){
        return $this->hasOne(Negocios::class,'id','negocio_id');
    }
}
