<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Illuminate\Http\Request;
use App\Repositories\CarroRepository;

class CarroController extends Controller{

    public function __construct(Carro $carro){
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $carroRepository = new CarroRepository($this->carro);

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        }else{
            $carroRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')){
            $carroRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $carroRepository->selectAtributos($request->atributos);
        }


            return response()->json($carroRepository->getResultado(),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate($this->carro->rules());

        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km,
        ]);
        
        return response()->json($carro,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $carro = $this->carro->with('modelos')->find($id);

        if(is_null($carro)) {
            return response()->json(["message"=> "Não foi possivel listar, registro não encontrado"],404);

        }
        return  response()->json($carro,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $carro = $this->carro->find($id);

        if(is_null($carro)) {
            return response()->json(["message"=> "Não foi possivel alterar, registro não encontrado"],404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach($carro->rules() as $input => $regra){
                if(array_key_exists($input,$request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
        } else{
            $request->validate($carro->rules());
        }


        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $carro = $this->carro->find($id);
        if(is_null($carro)) {
            return response()->json(['message'=> 'Não foi possivel apagar, registro não encontrado'],404);
        }
            
        $carro->delete();
        return 'Carro excluído com sucesso';
    }
}
