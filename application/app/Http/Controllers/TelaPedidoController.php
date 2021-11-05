<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TelaPedido;

class TelaPedidoController extends Controller
{
    public function __construct(){
		$this->middleware(function ($request, $next) {
			$value = session('user_logged');
			if(!$value){
				return redirect("/login");
			}
			return $next($request);
		});
	}

	public function index(){
		$telas = TelaPedido::all();
		return view('telasPedido/list')
		->with('telas', $telas)
		->with('title', 'Telas de Pedido');
	}

	public function new(){
		return view('telasPedido/register')
		->with('title', 'Cadastrar Tela de Pedido');
	}

	public function save(Request $request){
		$tela = new TelaPedido();
		$this->_validate($request);

		$result = $tela->create($request->all());

		if($result){

			session()->flash("mensagem_sucesso", "Tela cadastrada com sucesso.");
		}else{

			session()->flash('mensagem_erro', 'Erro ao cadastrar tela.');
		}
		
		return redirect('/telasPedido');
	}

	public function edit($id){
        $tela = new TelaPedido(); //Model

        $resp = $tela
        ->where('id', $id)->first();  

        return view('telasPedido/register')
        ->with('tela', $resp)
        ->with('title', 'Editar Tela de Pedido');

    }

    public function update(Request $request){
    	$tela = new TelaPedido();

    	$id = $request->input('id');
    	$resp = $tela
    	->where('id', $id)->first(); 

    	$this->_validate($request);
    	

        $resp->nome = $request->input('nome');
        $resp->alerta_amarelo = $request->input('alerta_amarelo');
    	$resp->alerta_vermelho = $request->input('alerta_vermelho');

    	$result = $resp->save();
    	if($result){
    		session()->flash('mensagem_sucesso', 'Tela editada com sucesso!');
    	}else{
    		session()->flash('mensagem_erro', 'Erro ao editar tela!');
    	}
    	
    	return redirect('/telasPedido'); 
    }

    public function delete($id){
        try{
        	$delete = TelaPedido::
        	where('id', $id)
        	->delete();
        	if($delete){
        		session()->flash('mensagem_sucesso', 'Registro removido!');
        	}else{
        		session()->flash('mensagem_erro', 'Erro!');
        	}
        	return redirect('/telasPedido');
        }catch(\Exception $e){
            return view('errors.sql')
            ->with('title', 'Erro ao deletar categoria de despesas')
            ->with('motivo', 'Não é possivel remover categorias presentes em contas!');
        }
    }


    private function _validate(Request $request){
    	$rules = [
    		'nome' => 'required|max:20'
    	];

    	$messages = [
    		'nome.required' => 'O campo nome é obrigatório.',
    		'nome.max' => '20 caracteres maximos permitidos.'
    	];
    	$this->validate($request, $rules, $messages);
    }
}
