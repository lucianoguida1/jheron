<?php 
Class AjaxController extends Controller{
	public function produtoAction(){
		if(!empty($_REQUEST['id'])){
			$id = (int) $_REQUEST['id'];$opt="";$pro = Produto::find($id);
			$re = "<script type='text/javascript'>
			$('#nome').val('".$pro->nome."');";
			$re .= "$('#fornecedor').html('".$opt."');</script>";
			echo $re;
		}
	}
	public function cadastrofornecAction($nome=false,$end=false,$tel=false){
		if($nome!=false && $end!=false && $tel!=false){
			$for = array('nome'=>$nome,'endereco'=>$end,'telefone'=>$tel,'user_id'=>Auth::userActive()->id);
			if(fornecedor::create($for)){
				echo '<script type="text/javascript">
				$("#close").trigger("click");
				$("select").each(function(){
					$(this).append("<option value=\''.fornecedor::last()->id.'\'>'.fornecedor::last()->nome.'</option>");
				});</script>';
			}else{
				echo false;
			}
		}
	}
	public function finalizarestoqueAction($id=""){
		if(!empty($id) && $id != ""){
			$prod = Produto::find($id);$cont =1;$qnad=0;$preco=0;
			$ingr = $prod->ingredientes;
			foreach($ingr as $val){
				foreach($val->snack->itens as $vall){
					foreach($prod->estoques as $valll){
						if($valll->status == 1 && $vall->pedido->data >= $valll->data && $vall->pedido->status != 3){
							$cont ++;
						}
					}
				}
			}
			if(!empty($prod)){
				foreach($prod->estoques as $val){
					if($val->status == 1 && $cont > 0){
						$qnad += $val->quantidade;
						$preco += $val->preco;
					}	
					$dat = $val->data;
					$val->data = $dat;
					$val->status = 0;
					$val->save();
				}
			}
			if($cont >0){
			$media = round($qnad/$cont,2);
			$preco = ($media * $preco)/$qnad;
				if($media != $qnad){
					foreach($ingr as $val){
						$val->quantidade = $media;
						$val->preco = $preco;
						$val->save();
					}
				}
			}
			echo true;
		}
	}
	public function dividacompletaAction($id){
		$pedidos = Pedido::find_all_by_telefone($id);$retorno = "";
		foreach($pedidos as $val){
			if($val->status == 4){
				$retorno .= "<tr>
				<td>";
					foreach($val->itens as $san){ 
						$retorno .= "<div class='well well-sm'><b>".$san->snack->nome.'</b><br>'.$san->ingr.'</div>';
					};
					$retorno .="</td><td>";
					foreach($val->itens as $san){ 
						foreach($san->adicionais as $add){
							$retorno .= '<div class="well well-sm"><b>'.$add->bebida->produto->nome.'</div>';
						}
					}
					$retorno .= "</td>
					<td>".$val->data->format('d/m/Y')."</td>
					<td>".$val->troco."</td>
					<td>
						<button class='btn btn-success form-control' onclick='recebido(".$val->id.")'>Recebido</button>

					</tr>";
				}
			}
			$retorno .="<script type='text/javascript'>$('#nome').html('".$val->nome."');</script>";
			echo($retorno);
		}
		public function recebidoAction($id){
			if(!empty(Pedido::find($id))){
				$ped = Pedido::find($id);
				$data = $ped->data;
				$ped->data = $data;
				$ped->status = 2;
				if($ped->save()){
					$pedidos = Pedido::find_all_by_telefone($ped->telefone);$retorno = "";
					foreach($pedidos as $val){
						if($val->status == 4){
							$retorno .= "<tr>
							<td>";
								foreach($val->itens as $san){ 
									$retorno .= "<div class='well well-sm'><b>".$san->snack->nome.'</b><br>'.$san->ingr.'</div>';
								};
								$retorno .="</td><td>";
								foreach($val->itens as $san){ 
									foreach($san->adicionais as $add){
										$retorno .= '<div class="well well-sm"><b>'.$add->bebida->produto->nome.'</div>';
									}
								}
								$retorno .= "</td>
								<td>".$val->data->format('d/m/Y')."</td>
								<td>".$val->troco."</td>
								<td>
									<button class='btn btn-success form-control' onclick='recebido(".$val->id.")'>Recebido</button>
									
								</tr>";
							}
						}
						$retorno .="<script type='text/javascript'>$('#nome').html('".$val->nome."');</script>";
						echo($retorno);
					}
				}
			}
			public function selectAction($tipo=''){
				if($tipo == "lanches" && isset($_REQUEST)){
					$id=(int)$_REQUEST['id'];$lan = Snack::find($id);$ingr="";
					foreach($lan->ingredientes as $val){ $ingr .= ", ".$val->produto->nome;}
					echo "<br><table class='table'>
					<thead>	
						<tr>
							<th>Nome</th>
							<th>Preco</th>
							<th>Ingredientes</th>
						</tr>
					</thead>
					<tbody>	
						<tr>
							<td>".$lan->nome."</td>
							<td>".$lan->preco."</td>
							<td>".$ingr."</td>
						</tr>
					</tbody>
				</table>
				<button class='btn btn-danger'>Excluir Lanche</button>
				";
			}elseif($tipo == "bebidas" && isset($_REQUEST)){
				$id=(int)$_REQUEST['id'];$lan = Bebida::find($id);$ingr="";
				echo "<br><table class='table'>
				<thead>	
					<tr>
						<th>Nome</th>
						<th>Preco</th>
						<th>Tamanho</th>
					</tr>
				</thead>
				<tbody>	
					<tr>
						<td>".$lan->produto->nome."</td>
						<td>R$ ".Estoque::find('last',array('conditions'=>array('produto_id = ?',$lan->produto->id)))->preco."</td>
						<td>".$lan->tamanho." ".Estoque::find('last',array('conditions'=>array('produto_id = ?',$lan->produto->id)))->medida."</td>
					</tr>
				</tbody>
			</table>
			<button class='btn btn-danger'>Excluir</button>
			";
		}
	}
}