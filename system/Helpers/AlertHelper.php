<?php
	class AlertHelper{
		
		/**
		 * Método responsável por exibir alertas da aplicação
		 * @param  array  $errors Conteúdo do alerta, com estilo; título, e; mensagem
		 * @param  string $style  Modo de exibição, sendo o padrão ou clean
		 * @return string         Alerta renderizado no padrão BootStrap 3
		 */
		public function show(array $errors = array(),$style=''){
			if(!empty($errors)){
				$style=$errors[0];
				$title=$errors[1];
				$message=isset($errors[2]) ? $errors[2] : '';
				$html= '<div id="alerts">
						<div class="alert alert-block alert-'.$style.'">
							<a href="#" data-dismiss="alert" class="close">&times;</a>
							<h4>'.$title.'</h4>
							'.$message.'
						</div>
					</div>';
				$html=(($style != 'clean') ? '
				<div class="container" id="content">
					<div class="col-md-12">
						'.$html.'
					</div>
				</div>' : $html);
				return $html;
			}
		}
	}