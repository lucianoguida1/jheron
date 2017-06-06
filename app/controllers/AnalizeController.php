<?php
	/**
	* 
	*/
	class AnalizeController extends Controller{
		
		
		public function indexAction(){
			# Code...
		}
		public function compraAction(){
			$produtos = Auth::userActive()->produtos;
			foreach($produtos as $val){
				foreach($val->estoques as $vall){
					$profor[$vall->fornecedor->nome][] = $val;
					if(isset($profor[$vall->fornecedor->nome]['total'])){
						$profor[$vall->fornecedor->nome]['total'] += $vall->preco;
					}else{$profor[$vall->fornecedor->nome]['total'] = $vall->preco;}
				}
			}
			foreach($produtos as $pro){
				$preco = 0;
				foreach($pro->estoques as $val){
					if($preco > $val->preco || $preco == 0){
						$preco = $val->preco;
						$for = $val->fornecedor->nome;
					}
					$data['barato'][$pro->nome] = array('preco'=>$preco,'fornecedor'=>$for);
				}
			}
			$data['dados']=($profor);
			$this->view('AnalizeCompra',$data,true);
		}
		public function vendaAction($tipo = ''){
			$date = date('Y-m-d');$data = array('btn0' => 'none','btn1' => 'none','btn2' =>'none','valor'=>'','dias'=>'');
			$sm = array(0=>'Dom',1=>'Seg',2=>'Ter',3=>'Qua',4=>'Qui',5=>'Sex',6=>'Sab');
			if($tipo == ""){
				$data['dados'] = Pedido::find_by_sql("SELECT * FROM `pedidos` WHERE MONTH(`data`) = MONTH('".$date."') AND `status` =2 AND user_id = ".Auth::userActive()->id.";");
			    for($i=1;$i<=31;$i++){
			        if(checkdate(date('m'), $i, date('Y'))){
			            $data['dado'][$i] =0;
			            $data['dias'] .= "'".$i.' '.$sm[date("w", mktime(0,0,0,date('m'),$i,date('Y')))]."',";
			            foreach($data['dados'] as $val){
			                if($val->data->format('d') == $i){
			                    $data['dado'][$i] += 1;
			                }
			            }
			        }
			    }
			    $data['btn0'] = 'active';
			}elseif($tipo == '1'){
				$data['dados'] = Pedido::find_by_sql("SELECT * FROM `pedidos` WHERE WEEK(`data`) = WEEK('".$date."') AND `status` =2 AND user_id = ".Auth::userActive()->id.";");
			    for($i=1;$i<=31;$i++){
			        if(checkdate(date('m'), $i, date('Y')) && date('W') == date("W", mktime(0,0,0,date('m'),$i,date('Y')))){
			            $data['dado'][$i] =0;
			            $data['dias'] .= "'".$i.' '.$sm[date("w", mktime(0,0,0,date('m'),$i,date('Y')))]."',";
			            foreach($data['dados'] as $val){
			                if($val->data->format('d') == $i){
			                    $data['dado'][$i] += 1;
			                }
			            }
			        }
			    }
			    $data['btn1'] = 'active';
			}elseif($tipo == '2'){
				$data['dados'] = Pedido::find_by_sql("SELECT * FROM `pedidos` WHERE YEAR(`data`) = YEAR('".$date."') AND `status` =2 AND user_id = ".Auth::userActive()->id.";");
			    for($i=1;$i<=12;$i++){
			            $data['dado'][$i] =0;
			            $data['dias'] .= "'".$i.' '.date("F", mktime(0,0,0,$i,date('d'),date('Y')))."',";
			            foreach($data['dados'] as $val){
			                if($val->data->format('m') == $i){
			                    $data['dado'][$i] += 1;
			                }
			            }
			        
			    }
			    $data['btn2'] = 'active';
			}
		    foreach($data['dado'] as $val){
		        $data['valor'] .= "'".$val."',";
		    }
			$this->view('AnalizeVenda',$data,true);
		}
		public function estoqueAction($opc = ''){
			if($opc == ''){
				$data['dados'] = Auth::userActive()->produtos;
				$data['lanches'] = Auth::userActive()->snacks;
				$this->view('AnalizeEstoque',$data,true);
			}
		}
		public function lucrosAction(){
			/* 
			*fazer um calculo antre quantiade de dinheiro que e gasta com compras é dirinheiro que entra na empresa
			*Mostra em tabela com todos os sanduiches
			*da possibilidade de lucros mensais etc
			*/
			$date = date('Y-m-d'); $data = array('venda'=>0,'compra'=>0);
			$venda = Pedido::find_by_sql("SELECT * FROM `pedidos` WHERE MONTH(`data`) = MONTH('".$date."') AND `status` =2 AND user_id = ".Auth::userActive()->id.";");
			$compras = Auth::userActive()->produtos;
			foreach($venda as $val){
				$data['venda'] += $val->valor;
			}
			foreach($compras as $val){
				$res =0;$preco=0;
				foreach($val->estoques as $vall){
					if($vall->status == 1){
						$res += $vall->quantidade;
						if($preco > $vall->preco || $preco==0){
							$preco = $vall->preco;
						}
					}
				}
				$data['compra'] += $res*$preco;
			}
			$data['vendaP'] = ($data['venda'] * 100)/$data['compra'];
			$data['vendaV'] = ($data['venda'] * 100)/$data['compra']-100;
			$data['vendaV'] = number_format($data['vendaV'], 2, '.', ',');
			if($data['vendaP'] > 100){
				$data['vendaP'] -=50;
			}
			$this->view('AnalizeLucro',$data,true);
		}
	}