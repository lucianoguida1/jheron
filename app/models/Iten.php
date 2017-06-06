<?php
	class Iten extends Model{
		static $belongs_to = array(
			array('pedido'),
			array('snack'),
			array('bebida')
		);
		static $has_many = array(
			array('adicionais')
		);
	}