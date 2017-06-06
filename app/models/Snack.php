<?php
	class Snack extends Model{
		
		/**
		 * Configuração para a associação entre tabelas
		 * @var array
		 */
		static $belongs_to = array(
			array('user')
		);
		static $has_many = array(
		 	array('ingredientes'),
		 	array('itens')
		);
	}