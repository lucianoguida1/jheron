<?php 
	class Error404Controller extends Controller{
		public function indexAction(){
			if(Auth::login_check()){
				$this->view('404');
			}else{
				$this->view('404','',true,'Generic');
			}
		}
	}