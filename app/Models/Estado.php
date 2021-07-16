<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;
    protected $table = 'estados';
    protected $fillable = ['uf_nome','uf_uf','uf_ibge','uf_ddd','uf_pais'];

    public function cidades(){
        return $this->hasMany(Cidade::class,'cd_uf','id');
    }
}
