<?php 
    class ProdutoController extends Controller{
    	public function indexAction(){
    		$this->redirectTo(SITE.'home');
    	}
        public function listProdutoAction($msg = ""){
            if($msg != ""){ $data['message']=$msg; }
            foreach(Auth::userActive()->produtos as $val){if($val->bebida == NULL){$data['dados'][] = $val;}}
            $this->view('ListarProd',$data,true);
        }
        public function adicionapAction(){
            if(empty($_REQUEST)){
                $data['dados'] = fornecedor::all();
                $this->view('AddProduto',$data,true);
            }else{
                if(!empty($_REQUEST['nome']) && !empty($_REQUEST['preco']) && !empty($_REQUEST['quantidade']) && !empty($_REQUEST['fornecedor'])){
                    $forn = $_REQUEST['fornecedor'];unset($_REQUEST['fornecedor']);
                    $_REQUEST['user_id'] = Auth::userActive()->id;
                    $qnt = $_REQUEST['quantidade'];unset($_REQUEST['quantidade']);
                    $preco = $_REQUEST['preco'];unset($_REQUEST['preco']);
                    $medida = $_REQUEST['medida'];unset($_REQUEST['medida']);
                    if(Produto::create($_REQUEST)){
                        Estoque::create(array('quantidade'=>$qnt,'preco'=>$preco,'medida'=>$medida,'produto_id'=>Produto::last()->id,'fornecedor_id'=>$forn,'status'=>1));
                        $this->listProdutoAction(array('success','Produto adicionado com sucesso!'));
                    }else{
                        $this->listProdutoAction(array('danger','infelizmente ocorreu erro ao adicionar o produto, Tente novamente!','caso o problema pressita contate o administrator'));
                    }
                }else{
                    $data['dados'] = fornecedor::all();
                    $data['message'] = array('danger','Informe os dados corretamente!');
                    $this->view('AddProduto',$data,true);
                }
            }
        }
        public function removerpAction($id=''){
            if($id != ''){$data['prodid']=$id;}
            if(empty($_REQUEST)){
                foreach(Auth::userActive()->produtos as $val){if($val->bebida == NULL){$data['dados'][] = $val;}}
                $this->view('ExclProduto',$data,true);
            }else{
                if(!empty($_REQUEST['produto'])){
                    $prod = Produto::find($_REQUEST['produto']);
                    $ing = $prod->ingredientes;
                    $stq = $prod->estoques;
                    if(!empty($ing)){
                        foreach($prod->ingredientes as $val){
                            $val->delete();
                        }
                    }
                    if(!empty($stq)){
                        foreach($prod->estoques as $val){
                            $val->delete();
                        }
                    }
                    if($prod->delete()){
                        $this->listProdutoAction(array('success','Produto excluido com sucesso!'));
                    }else{
                        $this->listProdutoAction(array('danger','infelizmente ocorreu erro ao excluir o produto, Tente novamente!','caso o problema pressita contate o administrator'));
                    }
                }else{
                    $data['dados'] = fornecedor::all();
                    $data['message'] = array('danger','Informe os dados corretamente!');
                    $this->view('ExclProduto',$data,true);
                }
            }
        }
        public function modificapAction($id=''){
            if($id != ''){$data['prodid']=$id;}
            if(empty($_REQUEST)){
                foreach(Auth::userActive()->produtos as $val){if($val->bebida == NULL){$data['dados'][] = $val;}}
                $this->view('ModProduto',$data,true);
            }else{
                if(!empty($_REQUEST['nome']) ){
                    $_REQUEST['user_id'] = Auth::userActive()->id;
                    $prod = Produto::find($_REQUEST['produto']);unset($_REQUEST['produto']);
                    if($prod->update_attributes($_REQUEST)){
                        $this->listProdutoAction(array('success','Produto modificado com sucesso!'));
                    }else{
                        $this->listProdutoAction(array('danger','infelizmente ocorreu erro ao modificar o produto, Tente novamente!','caso o problema pressita contate o administrator'));
                    }
                }else{
                    $data['dados'] = fornecedor::all();
                    $data['message'] = array('danger','Informe os dados corretamente!');
                    $this->view('ModProduto',$data,true);
                }
            }
        }
    	public function adicionarAction($tipo=''){
    		if(isset($_REQUEST) && empty($_REQUEST) && $tipo=='lanche'){
                $pros = Auth::userActive()->produtos;
                foreach($pros as $val){if($val->bebida == NULL){$data['dados'][]=$val;}}
    			$this->view('AddLanche',$data,true);
    		}elseif(isset($_REQUEST) && empty($_REQUEST) && $tipo=='bebida'){
                $data['dados'] = fornecedor::all();
                $this->view('AddBebida',$data,true);
            }elseif(isset($_REQUEST) && !empty($_REQUEST) && $tipo=='lanche'){
                if($this->insertL($_REQUEST)){
                    $this->listarAction(array('success','Sanduiche adicionado com sucesso!','Já esta disponivel para todos seus clientes.'));
                }else{
                    $this->listarAction(array('danger','infelizmente ocorreu erro ao adicionar seu lanche.','Tente novamente, caso o problema presista contae o administrador.'));
                }
            }elseif(isset($_REQUEST) && !empty($_REQUEST) && $tipo=='bebida'){
                if($this->insertB($_REQUEST)){
                    $this->listarbAction(array('success','Bebida adicionado com sucesso!','Já esta disponivel para todos seus clientes.'));
                }else{
                    $this->listarbAction(array('danger','infelizmente ocorreu erro ao adicionar sua bebida.','Tente novamente, caso o problema presista contae o administrador.'));
                }
            }
    	}
        public function modificarAction($tipo=''){
            if(isset($_REQUEST) && empty($_REQUEST) && $tipo=='lanche'){
                $data['dados'] = Auth::userActive()->snacks;
                $this->view('ModiLanche',$data,true);
            }elseif(isset($_REQUEST) && empty($_REQUEST) && $tipo=='bebida'){
                $pros = Auth::userActive()->produtos;
                foreach($pros as $val){if($val->bebida!=null){$dados[]=($val->bebida);}}
                $data['dados'] = $dados;
                $this->view('ModiBebida',$data,true);
            }elseif(isset($_REQUEST) && !empty($_REQUEST) && $tipo=='lanche'){
                if($this->modifica($_REQUEST)){
                    $this->listarAction(array('success','Sanduiche atualizado com sucesso!','Já esta disponivel para todos seus clientes.'));
                }else{
                    $this->listarAction(array('danger','infelizmente ocorreu erro ao modifica seu lanche.','Tente novamente, caso o problema presista contae o administrador.'));
                }
            }elseif(isset($_REQUEST) && !empty($_REQUEST) && $tipo=='bebida'){
                if($this->modificaB($_REQUEST)){
                    $this->listarbAction(array('success','Bebida atualizada com sucesso!','Já esta disponivel para todos seus clientes.'));
                }else{
                    $this->listarbAction(array('danger','infelizmente ocorreu erro ao modificar.','Tente novamente, caso o problema presista contae o administrador.'));
                }
            }
        }
        public function removerAction($tipo=""){
            if($tipo == 'lanche' && empty($_REQUEST)){
                $data['dados'] = Auth::userActive()->snacks;
                $this->view('ExclLanche',$data,true);
            }elseif($tipo == 'lanche' && !empty($_REQUEST)){
                $lan = Snack::find($_REQUEST['lanche']);
                if(!empty($lan)){
                    foreach ($lan->ingredientes as $val) {
                        $val->delete();
                    }$tes=$lan->itens;
                    if(!empty($tes)){
                        foreach($lan->itens as $val){
                            foreach($val->adicionais as $vall){
                                $vall->delete();
                            }
                            $val->delete();
                        }
                    }
                    if($lan->delete()){
                        $this->listarAction(array('success','Lanche <span class="danger">Excluido</span> com sucesso!'));
                    }else{
                        $this->listarAction(array('danger','infelizmente ocorreu erro ao <span class="danger">Excluido</span>!','caso o problema presista contate o administrador!'));
                    }
                }
            }elseif($tipo == 'bebida' && empty($_REQUEST)){
                $pros = Auth::userActive()->produtos;
                foreach($pros as $val){if($val->bebida!=null){$dados[]=($val->bebida);}}
                $data['dados'] = $dados;
                $this->view('ExclBebida',$data,true);
            }elseif($tipo == 'bebida' && !empty($_REQUEST)){
                $beb = bebida::find($_REQUEST['bebida']);
                if(!empty($beb)){
                    foreach($beb->adicionais as $val){$val->delete();}
                    $pro = $beb->produto;
                    foreach($pro->estoques as $val){$val->delete();}
                    if($beb->delete()){
                        $pro->delete();
                        $this->listarbAction(array('success','Bebida <span class="danger">Excluido</span> com sucesso!'));
                    }else{
                        $this->listarbAction(array('danger','infelizmente ocorreu erro ao <span class="danger">Excluido</span>!','caso o problema presista contate o administrador!'));
                    }
                }
            }
        }
        public function listarAction($msg=""){
            if($msg != ""){ $data['message']=$msg; }
            $data['lanches'] = Auth::userActive()->snacks;
            $this->view('ListaLanche',$data);
        }
        public function listarbAction($msg=""){
            if($msg != ""){ $data['message']=$msg; }
            foreach(Auth::userActive()->produtos as $val){
                $bebida = ($val->bebida);
                if(!empty($bebida)){
                    $data['bebidas'][] = $bebida;
                }
            }
            //$data['bebidas'] = Auth::userActive()->bebidas;
            $this->view('ListaBebida',$data);
        }
        public function buscaAction($tipo=''){
            if($tipo == 'lanche' && isset($_REQUEST['id']) && $_REQUEST['id'] > 0 && !empty(Snack::find((int)$_REQUEST['id']))){
                $lan = Snack::find((int)$_REQUEST['id']);
                $re= '<br>
                    <label for="nome">Nome do Sanduiche:</label>
                    <input class="form-control" type="text" name="nome" id="nome" value="'.$lan->nome.'" required="">
                    <div class="widget">
                        <fieldset>
                            <legend>Ingredientes:</legend>';
                            $ting = produto::all();
                            foreach($ting as $vall){
                                if($vall->bebida==NULL){
                                    $teste = true;
                                    foreach($lan->ingredientes as $val){   
                                        if($val->produto->id == $vall->id){
                                            $re .= '<label for="ing'.$val->produto->id.'" style="padding: 15px;">'.$val->produto->nome.'
                                                        <input type="checkbox" class="ingr" value="on" checked="checked" name="'.$val->produto->id.'" id="ing'.$val->produto->id.'">
                                                    </label>';$teste = false;
                                        }
                                    }
                                    if($teste){
                                        $re .= '<label for="ing'.$vall->id.'" style="padding: 15px;">'.$vall->nome.'
                                                    <input type="checkbox" class="ingr" value="off" name="'.$vall->id.'" id="ing'.$vall->id.'">
                                                </label>';
                                    }
                                }
                            }
                    $re .= '</fieldset>
                    </div>
                    <label for="preco">Preço:</label>
                    <div class="input-group">
                      <span class="input-group-addon">$</span>
                      <input type="text" class="form-control" name="preco" value="'.$lan->preco.'" id="preco" aria-label="Amount (to the nearest dollar)">
                    </div>

                    <br><button class="btn btn-primary">
                        <h3>Salvar</h3>
                    </button>
                <script type="text/javascript">
                    $("input:checkbox").change(function(){
                        if($(this).val() =="on"){
                            $(this).val("off");
                        }else{
                            $(this).val("on");
                        }
                    });
                    $("#form").submit(function() {
                        $("input:checkbox").each(function(){
                            if(!($(this).prop("checked"))){
                                $(this).prop("checked",true);
                            }
                        });
                    });
                </script>';
                echo $re;
            }elseif($tipo == 'bebida' && isset($_REQUEST['id']) && $_REQUEST['id'] > 0 && !empty(bebida::find((int)$_REQUEST['id']))){
                $bebida = Bebida::find($_REQUEST['id']);
                $re = '
                <label for="nome">Nome</label>
                <input class="form-control telefone" type="text" name="nome" id="nome" value="'.$bebida->produto->nome.'" required="">
                                    
                <label for="tamanho">tamanho:</label>
                <div class="input-group">
                    <input class="form-control telefone" type="text" name="tamanho" id="tamanho" value="'.$bebida->tamanho.'" required="">
                    <span class="input-group-addon" id="basic-addon2" style="padding: 0px;">
                    <select name="medida" style="padding: 5px 5px 5px 5px; font-weight: 800;width: 100px;">
                        <option value="Lt">Lt</option>
                        <option value="Ml">Ml</option>
                    </select>
                    </span>
                </div>
                
                <label for="valor">Preço para venda:</label>
                <div class="input-group">
                    <span class="input-group-addon">R$</span>
                    <input type="text" class="form-control" name="valor" id="valor" value="'.$bebida->valor.'" aria-label="Amount (to the nearest dollar)">
                </div>
                <br><button class="ui-button ui-widget ">
                    <h3>Finalizar Pedido</h3>
                </button>';
                echo $re;
            }
        }
        function insertB($inset){
            if(!empty($inset['nome']) && !empty($inset['tamanho']) && !empty($inset['preco']) && !empty($inset['medida']) &&  !empty($inset['quantidade']) &&  !empty($inset['fornecedor'])){
                $produto = array('nome'=>$_REQUEST['nome'],'user_id'=>Auth::userActive()->id);
                if(Produto::create($produto)){
                    $pro = Produto::last();
                    $bebida = array('tamanho'=>$inset['tamanho'],'produto_id'=>$pro->id,'valor'=>$inset['valor']);
                    $estoque = array('quantidade'=>$_REQUEST['quantidade'],'medida'=>"un",'preco'=>$_REQUEST['preco'],'status'=>1,'produto_id'=>$pro->id,'fornecedor_id'=>$_REQUEST['fornecedor']);
                    if(Bebida::create($bebida) && Estoque::create($estoque)){
                        return true;
                    }else{
                        $pro::delete();
                        return false;
                    }
                }else{
                    return false;
                }
            }
        }
        function modificaB($inset){
            if(!empty($inset['bebida']) && !empty($inset['nome']) && !empty($inset['tamanho']) && !empty($inset['medida']) ){
                $inner = Bebida::find($inset['bebida']);
                if(!empty($inner)){
                    $pro = $inner->produto;
                    $estoque = Estoque::find("last",array('conditions'=>array('produto_id = ?',$pro->id)));
                    $inner->tamanho = $inset['tamanho'];
                    $inner->valor = $inset['valor'];
                    $pro->nome = $inset['nome'];
                    $estoque->medida = $inset['medida'];
                    $inner->save();
                    $pro->save();
                    $estoque->save();
                    return true;
                }else{
                    return false;
                }
            }
        }
        function insertL($inset){
            if(isset($inset['nome']) && !empty($inset['nome']) && isset($inset['preco']) && !empty($inset['preco']) && ((int)$inset['preco']) > 0){
                $aux = $inset; unset($aux['nome'],$aux['preco']);
                if(!empty($aux)){
                    foreach($aux as $key => $val){
                        unset($aux['qtd'.$key]);
                        if($val == "on"){
                            if(!empty(Produto::find($key))){
                                $teste = TRUE;
                            }else{
                                $teste = FALSE;
                            }
                        }else{
                            unset($aux[$key]);
                        }
                    }
                    if($teste && Snack::create(array('nome'=>$inset['nome'],'preco'=>$inset['preco'],'observacoes'=>NULL,'user_id'=>Auth::userActive()->id))){
                        $id = Snack::last()->id;
                        foreach($aux as $key => $val){
                            Ingrediente::create(array('snack_id'=>$id,'produto_id'=>$key,'quantidade'=>0));
                        }
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        function modifica($san){
            $test = Snack::find($san['lanche']);
            if(!empty($test) && !empty($san['lanche']) && !empty($san['nome']) && !empty($san['preco'])){
                $aux = $san;unset($aux['lanche'],$aux['nome'],$aux['preco']);
                if(!empty($aux)){
                    foreach($aux as $key => $val){
                        if($val == 'off'){
                            foreach($test->ingredientes as $ingr){
                                if($ingr->produto->id == $key){
                                    $ingr->delete();
                                }
                            }
                        }elseif($val == 'on'){
                            $ts = true;
                            foreach($test->ingredientes as $ingr){
                                if($ingr->produto->id == $key){
                                    $ts= false;
                                }
                            }
                            if($ts){
                                Ingrediente::create(array('snack_id'=>$test->id,'produto_id'=>$key,'quantidade'=>0));
                            }
                        }
                    }
                }
                $test->nome = $san['nome'];$test->preco = $san['preco'];
                $test->save();
                return true;
            }
        }
    }