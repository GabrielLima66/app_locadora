<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{

    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return response()->json($this->marca->with('modelos')->get(),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        // $marca = Marca::create($request->all());
        $request->validate($this->marca->rules(),$this->marca->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        return response()->json($marca,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $marca = $this->marca->with('modelos')->find($id);

        if(is_null($marca)) {
            return response()->json(["message"=> "Não foi possivel listar, registro não encontrado"],404);

        }
        return  response()->json($marca,201);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        // $marca->update($request->all());
        $imagem_urn = '';
        $marca = $this->marca->find($id);

        if(is_null($marca)) {
            return response()->json(["message"=> "Não foi possivel alterar, registro não encontrado"],404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach($marca->rules() as $input => $regra){
                if(array_key_exists($input,$request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
        } else{
            $request->validate($marca->rules(),$marca->feedback());
        }
        //remove imagem antiga, caso tenha sido enviado no update
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens','public');
        }


        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;
        $marca->save();

        // $marca->update([
        //     'nome' => $request->nome,
        //     'imagem' => $imagem_urn
        // ]);

        return response()->json($marca,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        // $marca->delete();
        $marca = $this->marca->find($id);
        if(is_null($marca)) {
            return response()->json(['message'=> 'Não foi possivel apagar, registro não encontrado'],404);
        }

            Storage::disk('public')->delete($marca->imagem);
            
        $marca->delete();
        return 'Marca deletada com sucesso';
    }
}
