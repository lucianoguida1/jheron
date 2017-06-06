<?php
	class User extends Model{

		/**
		 * Configuração para a associação entre tabelas
		 * @var array
		 */
		static $has_many = array(
		 	array('login_attempts'),
			array('lost_passwords'),
			array('snacks'),
			array('pedidos'),
			array('bebidas'),
			array('produtos'),
			array('fornecedors')
		);
	}