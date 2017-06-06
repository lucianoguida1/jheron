<?php
	class Bebida extends Model{
		
		/**
		 * Configuração para a associação entre tabelas
		 * @var array
		 */
		static $belongs_to = array(
			array('produto'),
			array('fornecedor')
		);
		static $has_many = array(
		 	array('adicionais')
		);
	}