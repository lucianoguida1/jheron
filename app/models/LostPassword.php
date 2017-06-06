<?php
	class LostPassword extends Model{
		
		/**
		 * Configuração para a associação entre tabelas
		 * @var array
		 */
		static $belongs_to = array(
			array('user')
		);
	}