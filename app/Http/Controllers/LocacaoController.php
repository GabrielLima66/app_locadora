<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;


class LocacaoController extends Controller{
    public function __construct(Locacao $locacao){
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if($request->has('filtro')){
            $locacaoRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $locacaoRepository->selectAtributos($request->atributos);
        }

            return response()->json($locacaoRepository->getResultado(),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){


        $request->validate($this->locacao->rules());

        $locacao = $this->locacao->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final
        ]);

        return response()->json($locacao,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $locacao = $this->locacao->find($id);

        if(is_null($locacao)) {
            return response()->json(["message"=> "Não foi possivel listar, registro não encontrado"],404);
        }
        return  response()->json($locacao,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocacaoRequest  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $locacao = $this->locacao->find($id);

        if(is_null($locacao)) {
            return response()->json(["message"=> "Não foi possivel alterar, registro não encontrado"],404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach($locacao->rules() as $input => $regra){
                if(array_key_exists($input,$request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
        } else{
            $request->validate($locacao->rules());
        }


        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $locacao = $this->locacao->find($id);
        if(is_null($locacao)) {
            return response()->json(['message'=> 'Não foi possivel apagar, registro não encontrado'],404);
        }
            
        $locacao->delete();
        return 'Carro excluído com sucesso';
    }
}
