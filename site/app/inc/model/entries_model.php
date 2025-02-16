<?php
class entries_model extends DOLModel
{
	protected $field = array(" idx ", " symbol ", " entry_price ", " target ",  " entry_date ");
	protected $filter = array(" active = 'yes' ");
	function __construct($bd = false)
	{
		return parent::__construct("entries", $bd);
	}
}
