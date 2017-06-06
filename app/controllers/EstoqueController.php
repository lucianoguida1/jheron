<?php 

	class EstoqueController extends Controller{
		public function indexAction($msg = ""){
			if($msg != ""){ $data['message']=$msg; }
			$data['dados'] = Auth::userActive()->produtos;
			$data['fornecedor'] = Auth::userActive()->fornecedors;
			$this->view('AtualizarEstoque',$data);
		}
		public function finalizarAction(){
			$data['dados'] = Auth::userActive()->produtos;
			$this->view('Estoquefinalizar',$data);
		}
		public function atualizarAction(){
			foreach($_REQUEST as $key => $val){
				$expld = explode('-', $key);
				$inner[$expld[1]][$expld[0]] = $val;
				$inner[$expld[1]]['produto_id'] = $expld[1];
				$inner[$expld[1]]['medida'] = end(Produto::find($expld[1])->estoques)->medida;
			}
			foreach($inner as $val){
				if($val['quantidade'] >=1 && $val['preco'] >=0 ){
					$val['fornecedor_id'] = $val['fornecedor'];unset($val['fornecedor']);$val['status'] =1;
					$est = end(Produto::find($val['produto_id'])->estoques);
					if(Estoque::create($val)){
						$data = $est->data;
						$est->data = $data;
						$est->status =0;
						$est->save();
					}
				}
			}
			$this->indexAction(array('success','Estoque atualizado com sucesso!'));
		}
	}