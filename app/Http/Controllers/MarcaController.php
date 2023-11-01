<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class MarcaController extends Controller
{

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $marca = Marca::all();
        $marca = $this->marca->all();
        return $marca;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $marca = Marca::create($request->all());
        $marca = $this->marca->create($request->all());
        return $marca;
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);

        if(is_null($marca)) {
            return ['erro' => 'Registro não encontrado'];
        }
        return $marca;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $marca->update($request->all());
        $marca = $this->marca->find($id);
        if(is_null($marca)) {
            return ['erro'=> 'Não foi possivel realizar a atualização, registro não encontrado'];
        }
        $marca->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $marca->delete();
        $marca = $this->marca->find($id);
        if(is_null($marca)) {
            return ['erro'=> 'Não foi possivel apagar, registro não encontrado'];
        }
        $marca->delete();
        return 'Marca deletada com sucesso';
    }
}
