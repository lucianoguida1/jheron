<?php
/*
	STATUS = 0 : ANALIZE NAP EXECULTADA,
	STATUS = 2 : PEIDO CONCLUIDO;
	STATUS = 3 : CANCELADO;
	STATUS = 4 : CONCLUIDO FIADO;
*/
	class PedidoController extends Controller{
		public function indexAction($id = "",$conf=true){
			$test = true;
			if(!empty($_POST) && $conf){
				$ing ="";
				foreach($_POST as $key => $val){
					if($val == "off"){
						$ing .= "<span style='color: red;'>".Ingrediente::find($key)->produto->nome." </span>";
					}else{
						$ing .= "<span>".Ingrediente::find($key)->produto->nome." </span>";
					}
				}
				foreach ($_SESSION['sanduiches'] as $key => $value) {
					if(!isset($value['ing']) && $value['sanduiche'] == $id && $test ){
						$_SESSION['sanduiches'][$key]['ing'] = $ing;
						$test = false;
					}elseif($id == $key){
						$_SESSION['sanduiches'][$key]['ing'] = $ing;
					}
				}
			}
			foreach($_SESSION['sanduiches'] as $key => $value){
				if(!isset($value['ing'])){
					$this->ingrAction($value['sanduiche']);
				}
			}
			$this->view('Pedido','',true,'Generic');
		}
		public function ingrAction($idsan=""){
			if(empty($_SESSION['sanduiches'])){$_SESSION['sanduiches']=array();}
			if(empty($_SESSION['bebidas'])){$_SESSION['bebidas']=array();}
			if(!empty($_POST) && $idsan == ""){
				foreach($_POST as $key => $val){
					if($val > 0 && substr($key, -1) == "e"){
						for($i=0;$i<$val;$i++){
							$_SESSION['sanduiches'][]['sanduiche'] = (int)$key;
						}
						$san1 = empty($san1)?(int)$key:$san1;
					}elseif($val > 0 && substr($key, -1) == "a"){
						if(!isset($_SESSION['bebidas'][(int)$key])){
							$_SESSION['bebidas'][(int)$key] = $val;
						}else{
							$_SESSION['bebidas'][(int)$key] += $val;
						}
					}
				}
				if(!empty($san1) && isset($san1)){
					$data['dados'] = Snack::find($san1);
					$this->view('IngPedido',$data,true,'Generic');
				}else{
					$this->indexAction();
				}
			}elseif($idsan != ""){
				$data['dados'] = Snack::find($idsan);
				$this->view('IngPedido',$data,true,'Generic');
			}else{
				$this->redirectTo(SITE);
			}
		}
		public function removerAction($id = ""){
			if(isset($_SESSION['sanduiches'][$id]) && !empty($_SESSION['sanduiches'][$id])){
				unset($_SESSION['sanduiches'][$id]);
				$this->indexAction();
			}else{
				$this->indexAction();
			}
		}
		public function removerbAction($ib="",$id=""){
			if($ib!="" && $id!="" ){
				if(in_array($ib, $_SESSION['pedido'][$id],true)){
					$aux = $_SESSION['pedido'][$id];
					unset($aux['sanduiche'],$aux['ing']);
					foreach($aux as $key => $val){
						if($ib == $val){
							unset($_SESSION['pedido'][$id][$key]);
						}
					}
				}
			}
			$this->indexAction();
		}
		public function alteraAction($id = "guida"){
			if(isset($_SESSION['sanduiches'][$id]) && !empty($_SESSION['sanduiches'][$id])){
				$data['id'] = $id;
				$data['dados'] = Snack::find((int)$_SESSION['sanduiches'][$id]['sanduiche']);
				$this->view('AltPedido',$data,true,'Generic');
			}else{
				$this->indexAction();
			}
		}
		public function addbAction($id = ""){
			$data['bebidas'] = Bebida::find("all");
			$data['id'] = $id;
			if(isset($_POST) && !empty($_POST)){
				foreach($_POST as $key => $val){
					$_SESSION['pedido'][$id][$key] = $val;
				}
				$this->indexAction('',false);
			}else{
				$this->view('Addb',$data,true,'Generic');
			}
		}
		public function enderecoAction(){
			if(empty($_SESSION['pedido'])){
				header("location:".SITE);
			}
			$this->view('Endereco','',true,'generic');
		}
		public function concluirAction(){
			if(empty($_SESSION['pedido'])){
				$data['dados'] = Pedido::last();
				$this->view('PedCuncluido',$data,true,'generic');
			}else{
				$pedido = $_POST;$pedido['user_id'] = $_SESSION['id_e'];
				$pedido['telefone'] = preg_replace("/[^0-9]/","", $pedido['telefone']);
				$teste = true;
				$pedido['valor'] = $_SESSION['total'];
				if(Pedido::create($pedido)){
					$pid = Pedido::last()->id;
					foreach($_SESSION['pedido'] as $val){
						if(!empty($val)){
							$san = $val['sanduiche'];unset($val['sanduiche']);
							$ing = $val['ing'];unset($val['ing']);
							if(Iten::create(array('snack_id'=>$san,'ingr'=>$ing,'pedido_id'=>$pid))){
								$sanid = iten::last()->id;
								foreach($val as $idbb){
									Adicionai::create(array('bebida_id'=>$idbb,'iten_id'=>$sanid));
								}
							}else{
								$data['message'] = array(
									'danger',
									'Pidido não foi salvo, tente novamente!'
									);
								$teste = false;
								$pid = Pedido::last();
								$pid->delete();
							}
						}
					}
				}else{
					$data['message'] = array(
						'danger',
						'Pedido não foi salvo, tente novamente!'
						);
					$teste = false;
				}
				$data['dados'] = Pedido::last();
				unset($_SESSION['pedido']);
				$this->view('PedCuncluido',$data,true,'generic');
			}
		}
		public function cellAction($cel = ""){
			if($cel != "" && !empty(Pedido::find('all',array('conditions'=>array('telefone = ?',$cel)))[0])){
				$cel = preg_replace("/[^0-9]/","", $cel);
				$pedido = Pedido::find('all',array('conditions'=>array('telefone = ?',$cel)))[0];
				echo "<div class='well well-sm'><p><b>Temos seu ultimo endereco deseja carrega-lo ?</b><br>Rua: ".$pedido->rua."<br>N°: ".$pedido->numero."....</p><button type='button' class='btn btn-success' id='carrega'>Carregar Dados</button></div>
				<script type='text/javascript'>$('#carrega').click(function(){
					$('#nome').val('".$pedido->nome."');
					$('#setor').val('".$pedido->setor."');
					$('#rua').val('".$pedido->rua."');
					$('#numero').val('".$pedido->numero."');
					$('#referencia').val('".$pedido->referencia."');
					$('#retorno').hide('fast');
				})</script>
				";
			}
		}
	}