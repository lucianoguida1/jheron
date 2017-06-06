<?php
	class Estoque extends Model{
		static $belongs_to = array(
			array('fornecedor'),
			array('produto')
		);
	}