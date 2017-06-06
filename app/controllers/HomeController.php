<?php
	class HomeController extends Controller{
		public function __construct(){
			parent::__construct();
			Auth::redirectCheck();
		}
		public function indexAction($msg=""){
			$data = array();
			if($msg != ""){ $data['message']=$msg; }
			foreach(Auth::userActive()->pedidos as $val){
				if($val->status == 0){
					$data['pedidos'][] = $val;
				}
			}
			$this->view('Home',$data);
		}
		public function concluidoAction($id = ""){
			if($id != ""){
				$pedido = Pedido::find($id);$descont = true;$msg = "";
				if(!empty($pedido)){
					if($pedido->status != 2){
						$pedido->status = 2;
						foreach($pedido->itens as $val){
							foreach($val->snack->ingredientes as $vall){
								foreach($vall->produto->estoques as $valll){
									if($valll->status == 1 && $descont && $valll->quantidade>$vall->quantidade){
										$valll->quantidade = round($valll->quantidade - $vall->quantidade,2);
										$valll->save();
										$descont = false;
									}
									if($valll->quantidade<$vall->quantidade){
										$valll->status == 0;
										$valll->save();
									}
								}
								if($descont){
									$msg .= "Necessario avaliar o estoque de <b>".$vall->produto->nome."</b>.<br>";
								}
								$descont = true;
							}
						}
						$pedido->save();
						if($msg != ""){
							$this->indexAction(array('warning','Avalização de Estoque',$msg));
						}else{
							$this->indexAction();
						}
					}else{
						$this->redirectTo(SITE.'home');
					}
				}else{
					$this->redirectTo(SITE.'home');
				}
			}
		}
		public function concluidoFAction($id = ""){
			if($id != ""){
				$pedido = Pedido::find($id);$descont = true;$msg = "";
				if(!empty($pedido)){
					if($pedido->status != 4){
						$pedido->status = 4;
						foreach($pedido->itens as $val){
							foreach($val->snack->ingredientes as $vall){
								foreach($vall->produto->estoques as $valll){
									if($valll->status == 1 && $descont && $valll->quantidade>$vall->quantidade){
										$valll->quantidade = round($valll->quantidade - $vall->quantidade,2);
										$valll->save();
										$descont = false;
									}
									if($valll->quantidade<$vall->quantidade){
										$valll->status == 0;
										$valll->save();
									}
								}
								if($descont){
									$msg .= "Necessario avaliar o estoque de <b>".$vall->produto->nome."</b>.<br>";
								}
								$descont = true;
							}
						}
						$pedido->save();
						if($msg != ""){
							$this->indexAction(array('warning','Avalização de Estoque',$msg));
						}else{
							$this->indexAction();
						}
					}else{
						$this->redirectTo(SITE.'home');
					}
				}else{
					$this->redirectTo(SITE.'home');
				}
			}
		}
		public function cancelarAction($id = ""){
			if($id != ""){
				$pedido = Pedido::find($id);
				if(!empty($pedido)){
					if($pedido->status != 3){
						$pedido->status = 3;
						$pedido->save();
						$this->indexAction();
					}else{
						$this->redirectTo(SITE.'home');
					}
				}else{
					$this->redirectTo(SITE.'home');
				}
			}
		}
		public function vendasareceberAction(){
			$data = array();
			foreach(Auth::userActive()->pedidos as $val){
				if($val->status == 4){
					$data['dados'][$val->telefone] = $val;
					$data['divida'][$val->telefone] = isset($data['divida'][$val->telefone])? $data['divida'][$val->telefone] += $val->valor:$data['divida'][$val->telefone] = $val->valor;
				}
			}
			$this->view('Vendasareceber',$data);
		}
		public function atualizarAction($erro = ""){
			if($erro != ""){
				$return = "";
				foreach(Auth::userActive()->pedidos as $val){
					if($val->status == 0){
						$return .= "<tr><td>".$val->nome."</td><td>";
						foreach($val->itens as $san){ 
							$return .= "<div class='well well-sm'><b>".$san->snack->nome."</b><br>".$san->ingr."</div>";
						};
						$return .= "</td><td>";
						foreach($val->itens as $san){ 
							foreach($san->adicionais as $add){
								$return .= "<div class='well well-sm'><b>".$add->bebida->produto->nome."</div>";
							}
						}
						$return .= "</td><td>".$val->telefone."</td><td>".$val->troco."</td><td>
								Setor: ".$val->setor."<br>
								Rua: ".$val->rua."<br>
								Numero: ".$val->numero."
							</td>
							<td>
								<a href=".SITE."home/concluido/".$val->id." class='btn btn-success form-control'>Concluido</a>
								<a href=".SITE."home/concluidof/".$val->id." class='btn btn-warning form-control'>Concluido - Fiado</a>
								<a href=".SITE."home/cancelar/".$val->id." class='btn btn-danger form-control'>Cancela</a>
							</td>
						</tr>";
					}
				}
				if($return ==""){
					$return = '<tr><td colspan="7"><h1>Nada ainda por aqui</h1></td></tr>';
				}
				echo $return;
			}
		}
	}