<?php
	class PaginationHelper{

		/**
		 * Método responsável por retornar a barra de navegação para a paginação
		 * @param  string   $url    Link que será concatenado com as páginas
		 * @param  integer  $page   Página atual
		 * @param  integer  $total  Total de itens
		 * @param  integer  $limite Limite de itens a serem exibidos por página
		 * @return string   $html   HTML da paginação
		 */
		public static function showNav($url,$page,$total,$limite=7){
			$paginas=ceil($total / $limite);
			if($paginas > 1){
				$html='<ul class="pagination" style="margin:0 auto">';
				if ($page == 1)
					$html.='<li class="disabled"><a href="#">&laquo;</a></li>';
				else if($page > 6)
					$html.='<li><a href="'.$url.'">Primeira</a></li><li><a href="'.$url.'/'.($page-1).'">&laquo;</a></li>';
				else
					$html.='<li><a href="'.$url.'/'.($page-1).'">&laquo;</a></li>';
				foreach(array_reverse(range(($page-1), ($page-5))) as $pagina):
	   				if ($pagina > 0):
				        $html.='<li><a href="'.$url.'/'.$pagina.'">'.$pagina.'</a></li>';
	    			endif;
				endforeach;
				$html.='<li class="active"><a href="#">'.$page.'</a></li>';
				foreach(range(($page+1),($page+5)) as $pagina): 
					$last=(($paginas < 5) ? $paginas : 5);
				    if (($pagina-1) < $last): 
				   		$html.='<li><a href="'.$url.'/'.$pagina.'">'.$pagina.'</a></li>';
				    endif; 
				endforeach; 
				if ($page == $paginas)
				 	$html.='<li class="disabled"><a href="#">&raquo;</a></li><li class="disabled"><a href="#">&Uacute;ltima</a></li>';
				else
				 	$html.='<li><a href="'.$url.'/'.($page+1).'">&raquo;</a></li><li><a href="'.$url.'/'.$paginas.'">&Uacute;ltima</a></li>';
				return $html."</ul>";
			}
		}
	}