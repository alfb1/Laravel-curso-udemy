@extends('layouts.app')
@section('title', 'Lista de Produtos')
@section('content')
   <h1>Produtos</h1>
   @if($message = Session::get('success'))
      <div class="alert alert-success">
         {{$message}}
      </div>
   @endif
   <div class="row">
      <div class="col-md-12">
         <form method="POST" action="{{action('ProdutosController@ordem')}}">
            @csrf
            <div class="input-group mb-3">
               <select id="ordem" name="ordem" class="form-control">
                  <option>Escolha a ordem</option>
                  <option value="1" @if($ordem == 1) selected @endif>Título (A-Z)</option>
                  <option value="2" @if($ordem == 2) selected @endif>Título (Z-A)</option>
                  <option value="3" @if($ordem == 3) selected @endif>Valor (Maior-Menor)</option>
                  <option value="4" @if($ordem == 4) selected @endif>Valor (Menor-Maior)</option>
               </select>  
               <div class="input-group-append">
                  <button class="btn btn-outline-secondary">Ordenar</button>
               </div>
            </div>
         </form>
      </div>
      <div class="col-md-12">
         <form method="POST" action="{{action('ProdutosController@busca')}}">
            @csrf
            <div class="input-group mb-3">
               <input type="text" class="form-control" name="busca" id="busca" value="{{$buscar}}"
                      placeholder="Procurar produto no site...">  
               <div class="input-group-append">
                  <button class="btn btn-outline-secondary">Buscar</button>
               </div>
            </div>
         </form>
      </div>
   </div>
   <div class="row">
      @foreach ($produtos as $produto)
         <div class="col-md-3">
            @if (file_exists("./img/produtos/".md5($produto->id).'.jpg'))
               <img src="{{url('img/produtos/'.md5($produto->id).'.jpg')}}" alt="Imagem Produto" class="img-fluid img-thumbnail">
            @endif
            <h4 class="text-center"> 
               <a href="{{URL::to('produtos')}}/{{$produto->id}}">{{$produto->titulo}}</a>
               @if($produto->preco == $mais_caro)
                 <span class="badge badge-danger">Maior preço</span>
               @endif
               @if($produto->preco == $mais_barato)
                 <span class="badge badge-success">Menor preço</span>
               @endif
            </h4>
            <p class="text-center">R$ {{number_format($produto->preco, 2, ',', '.')}}</p>
            @if(Auth::check())
               <div class="md-3">
                  <form method="POST" action="{{action('ProdutosController@destroy', $produto->id)}}">
                     @csrf
                     <a href="{{URL::to('produtos/'.$produto->id.'/edit')}}" class="btn btn-primary">Editar</a> 
                     <input type="hidden" name="_method" value="DELETE">  
                     <button class="btn btn-danger">Deletar</button>
                  </form>
               </div>           
            @endif 
         </div>
      @endforeach
   </div>
   <div>
       <p><strong>O valor médio dos produtos é : R$ {{number_format($media, 2, ',', '.')}}</strong></p>
       <p><strong>O valor total dos produtos é : R$ {{number_format($soma, 2, ',', '.')}}</strong></p>
       <p><strong>O total de produtos é : {{number_format($contagem, 2, ',', '.')}}</strong></p>
       <p><strong>O total de produtos com preço maior do que 10 é : {{number_format($maior_dez, 2, ',', '.')}}</strong></p>
   </div>
   {{$produtos->links()}}
@endsection