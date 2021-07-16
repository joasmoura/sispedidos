<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;
    protected $table = 'pais';
    protected $fillable = ['ps_nome','ps_nome_pt','ps_sigla','ps-bacem'];

    public function estados(){
        return $this->hasMany(Estado::class,'uf_pais','id');
    }
}
