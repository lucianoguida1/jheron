<?php 
    class IndexController extends Controller{
        public function indexAction(){
            $_SESSION['id_e'] = 5;
        	if(!isset($_SESSION['n'])){$_SESSION['n']=1;}else{$_SESSION['n']++;}
        	$data['dados'] = Snack::find('all');
        	$data['bebidas'] = Bebida::find("all");
            $this->view('Index',$data,true,'Generic','',array(
            	CSS.'animate.css'
            ));
        }
    }