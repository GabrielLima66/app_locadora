<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return response()->json($this->modelo->with('marca')->get(),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos','public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        return response()->json($modelo,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $modelo = $this->modelo->with('marca')->find($id);

        if(is_null($modelo)) {
            return response()->json(["message"=> "Não foi possivel listar, registro não encontrado"],404);

        }
        return  response()->json($modelo,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $imagem_urn = '';
        $modelo = $this->modelo->find($id);

        if(is_null($modelo)) {
            return response()->json(["message"=> "Não foi possivel alterar, registro não encontrado"],404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach($modelo->rules() as $input => $regra){
                if(array_key_exists($input,$request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
        } else{
            $request->validate($modelo->rules());
        }
        //remove imagem antiga, caso tenha sido enviado no update
        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens/modelos','public');
        }
        

        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        $modelo->save();

        return response()->json($modelo,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $modelo = $this->modelo->find($id);
        if(is_null($modelo)) {
            return response()->json(['message'=> 'Não foi possivel apagar, registro não encontrado'],404);
        }

            Storage::disk('public')->delete($modelo->imagem);
            
        $modelo->delete();
        return 'Modelo deletado com sucesso';
    }
}
