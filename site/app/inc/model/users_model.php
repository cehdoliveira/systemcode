<?php 
class users_model extends DOLModel{
	protected $field = array( " idx " , " name " , " link " , " img " ,  " img_mobile " , " target " , " slug " ) ;
	protected $filter = array( " active = 'yes' " ) ;
	function __construct( $bd = false  ) {
		return parent::__construct( "users" , $bd );
	}
}