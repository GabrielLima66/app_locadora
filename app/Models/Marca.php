<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome','imagem'];

    public function rules(){
        return [
            "nome"=> 'required|unique:marcas,nome,'.$this->id,
            "imagem"=> 'required|file|mimes:png'
        ];
    }
    public function feedback(){
        return [
            'required'=>'O campo :attribute é obrigatório',
            'nome.unique'=>'A marca já existe',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG'
        ];
    }
    /**
     * Get all of the comments for the Marca
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelos(){
        return $this->hasMany('App\Models\Modelo');
    }
}
