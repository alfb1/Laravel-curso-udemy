<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Produtos;

class ProdutosController extends Controller
{
    public function index(){
       // antes - 29-10-19
       // $produtos = Produtos::all();
       $produtos = Produtos::paginate(10);
       //    08-11-19
       $mais_caro = Produtos::all()->max('preco');
       $mais_barato = Produtos::all()->min('preco');
       $media = Produtos::all()->avg('preco');
       $soma = Produtos::all()->sum('preco');
       $contagem = Produtos::all()->count();
       $maior_dez = Produtos::where('preco', '>', 10)->count();

       return view('produtos.index', array('produtos'=>$produtos,
                   "buscar"=>null, 'ordem'=>null,
                   'mais_caro'=>$mais_caro ,
                   'mais_barato'=>$mais_barato,
                   'media'=>$media,
                   'soma'=>$soma,
                   'contagem'=>$contagem,
                   'maior_dez'=>$maior_dez,
                ));
    }

    public function show($id){
        $produto = Produtos::find($id);
        return view('produtos.show', array('produto'=>$produto));
    }

    public function create(){
        if ( Auth::check()){
           return view('produtos.create');
        }else{
            return redirect('login');
        }
    }

    public function store(Request $request){

        $this->validate( $request, [
             'sku'=>'required|unique:produtos|min:3',
             'titulo'=>'required|min:3',
             'descricao'=>'required|min:10',
             'preco'=>'required|numeric',
        ]);
        $produto = new Produtos();
        $produto->sku = $request->input('sku');
        $produto->titulo = $request->input('titulo');
        $produto->descricao = $request->input('descricao');
        $produto->preco = $request->input('preco');

        if($produto->save()){
            return redirect('produtos/create')->with('success', 'Produto Cadastrado com Sucesso !!!');
        }

    }

    public function edit($id){
        if ( Auth::check()){
            $produto = Produtos::find($id);
            return view('produtos.edit', compact('produto', 'id'));
         }else{
             return redirect('login');
         }
    }

    public function update(Request $request, $id){
        
        $produto = Produtos::find($id);

        $this->validate( $request, [
             'sku'      => 'required|min:3',
             'titulo'   => 'required|min:3',
             'descricao'=> 'required|min:10',
             'preco'    => 'required|numeric',
        ]);

        if( $request->hasFile('img_produto'))
        {
          $imagem       = $request->file('img_produto');
          $nome_arquivo = md5($id).".".$imagem->getClientOriginalExtension();
          $request->file('img_produto')->move(public_path('./img/produtos/'), $nome_arquivo);
        }

        $produto->sku       = $request->get('sku');
        $produto->titulo    = $request->get('titulo');
        $produto->descricao = $request->get('descricao');
        $produto->preco     = $request->get('preco');

        if($produto->save()){
            return redirect('produtos/'.$id.'/edit')->with('success', 'Produto atualizado com Sucesso !!!');
        }

    }

    public function destroy($id){
        $produto = Produtos::find($id); 

        if (file_exists("./img/produtos/".md5($produto->id).'.jpg')){
            unlink("./img/produtos/".md5($produto->id).'.jpg');
        }

        $produto->delete();

        return redirect()->back()->with('success', 'Produto deletado com Sucesso !!!');

    }

    public function busca(Request $request){
    //   antes - 29-10-19
    //   $produtos = Produtos::where('titulo', 'LIKE', '%'.$request->input('busca').'%')
    //                     ->orwhere('descricao', 'LIKE', '%'.$request->input('busca').'%')
    //                     ->get();

       $produtos = Produtos::where('titulo', 'LIKE', '%'.$request->input('busca').'%')
                                    ->orwhere('descricao', 'LIKE', '%'.$request->input('busca').'%')
                                    ->paginate(8);
      return view('produtos.index', array('produtos'=>$produtos, 'buscar'=>$request->input('busca'), 'ordem'=>null));
    }

    public function ordem(Request $request){
    // 04-11-19
        $ordemInp = $request->input('ordem');

        if( $ordemInp == 1){
           $campo = 'titulo';
           $tipo  = 'asc';
        }else if( $ordemInp ==2){
            $campo = 'titulo';
            $tipo  = 'desc';
        }else if( $ordemInp ==3){
            $campo = 'preco';
            $tipo  = 'desc';
        }else if( $ordemInp ==4){
            $campo = 'preco';
            $tipo  = 'asc';
        }   


        $produtos = Produtos::orderBy($campo, $tipo)->paginate(10);
        return view('produtos.index', array('produtos'=>$produtos, 'buscar'=>null, 'ordem'=>$ordemInp));
    }
}
