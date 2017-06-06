<?php
	class EmailHelper{
		
		/**
		 * Método responsável pelo envio de emails
		 * @param  string $email    E-mail para qual será enviada a mensagem
		 * @param  string $assunto  Assunto da mensagem
		 * @param  string $mensagem Mensagem
		 * @param  array  $config   Array com Remetente e E-mail do remetente
		 * @return array            Array com o status de envio e mensagem
		 */
		public function enviar($email,$assunto,$mensagem,$config=array()){
			
			$destinatario=strtolower($email);
			$assunto=addslashes(trim($assunto));
			$mensagem=nl2br($mensagem);
			
			$remetente      =	$config["remetente"];
			$e_remetente	=	$config["email"];

			$cabecalho = "MIME-Version: 1.0\n";
			$cabecalho .= 	"Content-Type: text/html; charset=UTF-8\n";
			$cabecalho .= 	"From: \"{$remetente}\" <{$e_remetente}>\n";

			#$status_envio = @mail ($destinatario, $assunto, $mensagem, $cabecalho); 

			$msg=(($status_envio !== true) ? "Resposta enviada com sucesso!" : "Ops! Houve um erro no envio da resposta!");	

			return array($status_envio,$msg);
		}
	}