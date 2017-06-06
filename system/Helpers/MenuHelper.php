<?php
	class MenuHelper{

		/**
		 * Método responsável por definir o Array com menus e submenus
		 * @param  string $role Role do usuário
		 * @return array  $menu Array com os menus
		 */
		public static function menus($role){
			switch ($role) {
				case 'administrator':
					$menu=array(
						"Home/home"=>"home",
						"Projetos/briefcase"=>"projetos/listar/",
						"Clientes/users"=>array(
							"Listar todos"=>"clientes/show",
							"Tipos de clientes"=>"clientes/tipos"
						),
						"Usuários/users"=>"usuarios/listar/"
					);
					break;
				case 'user':
					$menu=array(
						"Inicio/home"=>"home",
						"Lanches/cutlery"=>array(
							"Meus lanches" => 'produto/listar',
							"Adicionar" => 'produto/adicionar/lanche',
							"Modificar" => 'produto/modificar/lanche',
							"Excluir" => 'produto/remover/lanche'
						),
						"Bebidas/glass"=>array(
							"Minhas Bebidas" => 'produto/listarb',
							"Adicionar" => 'produto/adicionar/bebida',
							"Modificar" => 'produto/modificar/bebida',
							"Excluir" => 'produto/remover/bebida'
						),
						"Produtos/shopping-cart"=>array(
							"Listar Todos" => 'produto/listProduto',
							"Adicionar Ingrediente" => 'produto/adicionap/',
							"Modificar Ingrediente" => 'produto/modificap/',
							"Excluir" => 'produto/removerp/'
						),
						"Analize/area-chart"=>array(
							#"Igredientes"=>'analize/estoque',
							'Vendas'=>'analize/venda',
							#'Ganhos'=>'analize/lucros',
							'Compras'=>'analize/compra'
						),
						/*"Estoque/archive"=>array(
							'Lançar Compra'=>'estoque',
							'Baixar no Estoque'=>'estoque/finalizar'
						),*/
						'Vendas a Receber/money'=>'home/vendasareceber'
					);
					break;
			}
			return $menu;
		}
	
		/**
		 * Método responsável por renderizar o menu em HTML
		 * @param  object $user       Usuário logado
		 * @param  string $controller Controller atual
		 * @return string $html       HTML do menu
		 */
		public static function render($user,$controller){
			if(is_null($user))
				return;
			$menus=self::menus($user->role);
			$controller=str_replace("Controller", "", $controller);
			$html='';
			foreach ($menus as $key => $value) {
				$explode=explode("/",$key);
				$title=$explode[0];
				$icon=$explode[1];
				if(is_array($value)){
					$values=array_values($value);
					$check=explode("/",$values[0]);
					$html.='
					    <li class="dropdown '.(($check[0] == $controller) ? 'active' : '').'">
					      <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-'.$icon.'"></i> <span>'.$title.'</span> <i class="arrow fa fa-angle-down pull-right"></i></a>
					      <ul class="dropdown-menu">
					';
					foreach($value as $titulo => $link){
						$html.='<li><a href="'.SITE.$link.'">'.$titulo.'</a></li>';
					}
					$html.='</ul></li>';
				}else{
					$html.='<li '.((strpos($value, $controller) !== false) ? 'class="active"' : '').'><a href="'.SITE.$value.'"><i class="fa fa-'.$icon.'"></i> <span>'.$title.'</span></a></li>';
				}
			}
			return $html;
		}
	}