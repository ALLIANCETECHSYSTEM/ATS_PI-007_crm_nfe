@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($produto) ? "Editar": "Cadastrar" }}} Produto</h4>
		<form method="post" action="{{{ isset($produto) ? '/produtos/update': '/produtos/save' }}}">
			<input type="hidden" name="id" value="{{{ isset($produto->id) ? $produto->id : 0 }}}">
			<section class="section-1">
				<div class="row">
					<div class="input-field col s8">
						<input value="{{{ isset($produto->nome) ? $produto->nome : old('nome') }}}" id="name" name="nome" type="text" class="validate">
						<label for="name">Nome</label>

						@if($errors->has('nome'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('nome') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s3">
						<input value="{{{ isset($produto->valor_venda) ? $produto->valor_venda : old('valor_venda') }}}" id="valor" name="valor_venda" type="text" class="validate">
						<label for="value_sale">Valor de Venda</label>

						@if($errors->has('valor_venda'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('valor_venda') }}</span>
						</div>
						@endif

					</div>

					<div class="col s2">
							<label>Valor Livre</label>

							<div class="switch">
								<label class="">
									Não
									<input @if(isset($produto->valor_livre) && $produto->valor_livre) checked @endisset value="true" name="valor_livre" class="red-text" type="checkbox">
									<span class="lever"></span>
									Sim
								</label>
							</div>
						</div>

					<div class="input-field col s3">

						<select name="categoria_id">
							@foreach($categorias as $cat)
							<option value="{{$cat->id}}"
								@isset($produto)
								@if($cat->id == $produto->categoria_id)
								selected=""
								@endif
								@endisset >{{$cat->nome}}</option>

								@endforeach
							</select>
							<label>Categoria</label>
							@if($errors->has('categoria_id'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('categoria_id') }}</span>
							</div>
							@endif

						</div>
					</div>

					<div class="row">
						<div class="input-field col s4">
							<select name="cor">
								<option value="--">--</option>
								<option value="Preto">Preto</option>
								<option value="Branco">Branco</option>
								<option value="Dourado">Dourado</option>
								<option value="Vermelho">Vermelho</option>
								<option value="Azul">Azul</option>
								<option value="Rosa">Rosa</option>
							</select>
							<label>Cor (Opcional)</label>
						</div>
					</div>

					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</section>

				

				<section class="section-2">
					<div class="row">
						<div class="input-field col s3">
							<select id="unidade_compra" name="unidade_compra">
								@foreach($unidadesDeMedida as $u)
								<option @if(isset($produto))
								@if($u == $produto->unidade_compra)
								selected
								@endif
								@else
								@if($u == 'UNID') 
								selected 
								@endif 
								@endif value="{{$u}}">{{$u}}</option>
								@endforeach
							</select>
							<label for="unidade_compra">Unidade de compra</label>
						</div>
						<div class="input-field col s2" id="conversao" style="display: none">
							<input value="{{{ isset($produto->conversao_unitaria) ? $produto->conversao_unitaria : old('conversao_unitaria') }}}" id="conversao_unitaria" name="conversao_unitaria" type="text" class="validate">
							<label for="conversao_unitaria">Conversão Unitária</label>

							@if($errors->has('conversao_unitaria'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('conversao_unitaria') }}</span>
							</div>
							@endif
						</div>

						<div class="input-field col s3">
							<select id="unidade_venda" name="unidade_venda">
								@foreach($unidadesDeMedida as $u)
								<option @if(isset($produto))
								@if($u == $produto->unidade_venda)
								selected
								@endif
								@else
								@if($u == 'UNID') 
								selected 
								@endif 
								@endif value="{{$u}}">{{$u}}</option>
								@endforeach
							</select>
							<label>Unidade de venda</label>
						</div>
					</div>

					<div class="row">
						<div class="input-field col s2">
							<input value="{{{ isset($produto->NCM) ? $produto->NCM : old('NCM') }}}" id="ncm" name="NCM" type="text" class="validate">
							<label for="NCM">NCM</label>

							@if($errors->has('NCM'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('NCM') }}</span>
							</div>
							@endif

						</div>

						<div class="input-field col s2">
							<input value="{{{ isset($produto->CEST) ? $produto->CEST : old('CEST') }}}" id="cest" name="CEST" type="text" class="validate">
							<label for="CEST">CEST</label>

							@if($errors->has('CEST'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('CEST') }}</span>
							</div>
							@endif

						</div>

					</div>

					<div class="row">

						<div class="input-field col s8">
							
							<select name="CST_CSOSN">
								@foreach($listaCSTCSOSN as $key => $c)
								<option value="{{$key}}"
								@if($config != null)
								@if(isset($produto))
								@if($key == $produto->CST_CSOSN)
								selected
								@endif
								@else
								@if($key == $config->CST_CSOSN_padrao)
								selected
								@endif
								@endif

								@endif
								>{{$key}} - {{$c}}</option>
								@endforeach
							</select>

							<label for="CEST">CST/CSOSN</label>

						</div>
					</div>

					<div class="row">
						<div class="input-field col s5">
							
							<select name="CST_PIS">
								@foreach($listaCST_PIS_COFINS as $key => $c)
								<option value="{{$key}}"
								@if($config != null)
								@if(isset($produto))
								@if($key == $produto->CST_PIS)
								selected
								@endif
								@else
								@if($key == $config->CST_PIS_padrao)
								selected
								@endif
								@endif

								@endif
								>{{$key}} - {{$c}}</option>
								@endforeach
							</select>

							<label for="CEST">CST PIS</label>

						</div>

						<div class="input-field col s5">
							
							<select name="CST_COFINS">
								@foreach($listaCST_PIS_COFINS as $key => $c)
								<option value="{{$key}}"
								@if($config != null)
								@if(isset($produto))
								@if($key == $produto->CST_COFINS)
								selected
								@endif
								@else
								@if($key == $config->CST_COFINS_padrao)
								selected
								@endif
								@endif

								@endif
								>{{$key}} - {{$c}}</option>
								@endforeach
							</select>

							<label for="CEST">CST COFINS</label>

						</div>
					</div>

					<div class="row">
						<div class="input-field col s5">
							
							<select name="CST_IPI">
								@foreach($listaCST_IPI as $key => $c)
								<option value="{{$key}}"
								@if($config != null)
								@if(isset($produto))
								@if($key == $produto->CST_IPI)
								selected
								@endif
								@else
								@if($key == $config->CST_IPI_padrao)
								selected
								@endif
								@endif

								@endif
								>{{$key}} - {{$c}}</option>
								@endforeach
							</select>

							<label for="CEST">CST IPI</label>

						</div>
					</div>


					<div class="row">
						<div class="input-field col s3">
							<input value="{{{ isset($produto->codBarras) ? $produto->codBarras : old('codBarras') }}}" id="codBarras" name="codBarras" type="text" class="validate">
							<label for="codBarras">Código de Barras EAN13</label>

							@if($errors->has('codBarras'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('codBarras') }}</span>
							</div>
							@endif

						</div> 
					</div>

					<div class="row">
						<div class="input-field col s2">
							<input value="{{{ isset($produto->perc_icms) ? $produto->perc_icms : $tributacao->icms }}}" id="perc_icms" name="perc_icms" type="text" class="validate">
							<label for="perc_icms">%ICMS</label>

							@if($errors->has('perc_icms'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('perc_icms') }}</span>
							</div>
							@endif

						</div> 
						<div class="input-field col s2">
							<input value="{{{ isset($produto->perc_pis) ? $produto->perc_pis : $tributacao->pis }}}" id="perc_pis" name="perc_pis" type="text" class="validate">
							<label for="perc_pis">%PIS</label>

							@if($errors->has('perc_pis'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('perc_pis') }}</span>
							</div>
							@endif

						</div> 
						<div class="input-field col s2">
							<input value="{{{ isset($produto->perc_cofins) ? $produto->perc_cofins : $tributacao->icms }}}" id="perc_cofins" name="perc_cofins" type="text" class="validate">
							<label for="perc_cofins">%COFINS</label>

							@if($errors->has('perc_cofins'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('perc_cofins') }}</span>
							</div>
							@endif

						</div> 
						<div class="input-field col s2">
							<input value="{{{ isset($produto->perc_ipi) ? $produto->perc_ipi : $tributacao->icms }}}" id="perc_ipi" name="perc_ipi" type="text" class="validate">
							<label for="perc_ipi">%IPI</label>

							@if($errors->has('perc_ipi'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('perc_ipi') }}</span>
							</div>
							@endif

						</div> 
					</div>
					

					<div class="row">
						<div class="col s2">
							<label>Composto</label>

							<div class="switch">
								<label class="">
									Não
									<input @if(isset($produto->composto) && $produto->composto) checked @endisset value="true" name="composto" class="red-text" type="checkbox">
									<span class="lever"></span>
									Sim
								</label>
							</div>
						</div>

						<div class="col s10">
							<p class="red-text">*Produzido no estabelecimento composto de outros produtos já cadastrados, deverá ser criado uma receita para redução de estoque. </p>
						</div>
					</div>



				</section>
				
				<br>
				<div class="row">
					<a class="btn-large red" href="/produtos">Cancelar</a>
					<input type="submit" value="Salvar" class="btn-large green accent-3">
				</div>
			</form>
		</div>
	</div>
	@endsection