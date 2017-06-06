<?php
	class Ingrediente extends Model{
		
		/**
		 * Configuração para a associação entre tabelas
		 * @var array
		 */
		static $belongs_to = array(
			array('snack'),
			array('produto')
		);
	}