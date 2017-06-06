<?php 
    class LoginController extends Controller{
    	public function __construct(){
    		parent::__construct();
    		Auth::redirectCheck(true);
    	}
        public function cadastroAction($msg = ""){
            if($msg != ""){ $data['message'] = $msg; }else{ $data = "";}
            $this->view('Cadastro','',true,"generic");
        }
        public function cadastrarAction(){
            $validetor = new Validator($_REQUEST);
            $validetor->field_filledIn($_REQUEST);
            $validetor->field_username('login');
            $validetor->field_numeric('telefone');
            $validetor->field_email("email");
            if($validetor->valid){
                $user['username'] = DataHelper::tratar($_REQUEST['login'],'sl');
                $credencial = Tools::hashHX($_REQUEST['senha']);
                $user['password'] = $credencial['password'];
                $user['salt'] = $credencial['salt'];
                $user['role'] = "user";
                $user['status'] = 2;
                $user['full_name'] = $_REQUEST['full_name'];
                $user['email'] = $_REQUEST['email'];
                $user['telefone'] = $_REQUEST['telefone'];
                if(User::create($user)){
                    $this->indexAction(array('warning','Usuario criado com sucesso!','Aguarde a aprovação para fazer login!'));
                }
            }else{
                $this->cadastroAction($validetor->getErrors());
            }
        }
        public function indexAction($msg = ""){
            if($msg != ""){ $data['message'] = $msg; }else{ $data = "";}
            $this->view('Login',$data,true,'Generic','',array(CSS.'animate.css'));
        }
        public function logarAction(){
    		$data=array();
    		//Validação
    		$validator=new Validator($_REQUEST);
    		$validator->field_filledIn();
            $validator->field_username('username');

    		if(!$validator->valid){
    			$data["message"]=$validator->getErrors();
    		}else{
    			//Tratamento
    			$super_global=DataHelper::tratamento($_REQUEST,INPUT_POST);

    			//Autenticação
    			$auth=new Auth;
    			$auth->login($super_global["username"],$super_global["password"]);
    			if($auth->check()){
    				$this->redirectTo(SITE."home");
    			}else{
    				$data["message"]=$auth->getErrors();
    			}
    		}
    		$this->view('Login',$data,true,'Generic');
        }
        public function sairAction(){
        	Auth::logout();
        }
    }