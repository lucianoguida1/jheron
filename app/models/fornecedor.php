<?php
	class fornecedor extends Model{
		static $belongs_to = array(
			array('user')
		);
		static $has_many = array(
		 	array('bebidas')
		);
		static $has_one = array(
			array('estoque')
		);
	}