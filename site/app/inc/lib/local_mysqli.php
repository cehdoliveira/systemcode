<?php
class local_mysqli extends mysqli
{
	public function __construct($sys = NULL)
	{
		$host = defined("cHStr") ? constant("cHStr") : 'localhost';
		$user = defined("cUserStr") ? constant("cUserStr") : 'root';
		$pass = defined("cPassStr") ? constant("cPassStr") : '';
		$db = defined("cBancoStr") ? constant("cBancoStr") : 'test';

		// Chamando o construtor da classe mysqli corretamente
		parent::__construct($host, $user, $pass, $db);

		if ($this->connect_errno) {
			throw new Exception('Connect Error (' . $this->connect_errno . ') ' . $this->connect_error);
		}

		$this->set_charset("utf8mb4");
	}

	function select($fields, $table, $options)
	{
		$res = $this->my_query(
			sprintf(
				"SELECT %s FROM %s %s",
				$fields,
				$table,
				$options
			)
		);
		return $res;
	}

	function insert($fields, $table, $options)
	{
		return $this->my_query(
			sprintf(
				"INSERT INTO %s SET %s %s",
				$table,
				$fields,
				$options
			)
		);
	}

	function replace($fields, $table)
	{
		return $this->my_query(
			sprintf(
				"REPLACE INTO %s SET %s",
				$table,
				$fields
			)
		);
	}

	function update($fields, $table, $options)
	{
		return $this->my_query(
			sprintf(
				"UPDATE %s SET %s %s",
				$table,
				$fields,
				$options
			)
		);
	}

	function delete($table, $options)
	{
		return $this->my_query(
			sprintf(
				"DELETE FROM %s %s",
				$table,
				$options
			)
		);
	}

	function my_query($query)
	{
		$r = $this->query($query) or die("SQL error: $query \n " . $this->error);
		return $r;
	}

	function recordcount($res)
	{
		return is_object($res) ? (int)$res->num_rows : 0;
	}

	function result($res, $name, $position)
	{
		if ($res === false) return false;
		if ($position >= $res->num_rows) return false;
		$res->data_seek($position);
		$line = $res->fetch_array(MYSQLI_ASSOC);
		return isset($line[$name]) ? $line[$name] : false;
	}

	function results($res)
	{
		$obj = array();
		if ((int)$this->recordcount($res) > 0) {
			while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$obj[] = $row;
			}
		}
		return $obj;
	}

	function fields_config($table)
	{
		$object = array();
		$res = $this->my_query(
			sprintf(
				"SHOW COLUMNS FROM %s",
				$table
			)
		);

		foreach ($this->results($res) as $key => $data) {
			if ($data["Key"] == "PRI") {
				$object[$data["Field"]]["PK"] = true;
			}
			if ($data["Key"] == "UNI") {
				$object[$data["Field"]]["UNI"] = true;
			}
			if (preg_match("/(?P<TYPE>\w+)\((?P<SIZE>.+)\)/", $data["Type"], $match)) {
				$object[$data["Field"]]["type"] = $match["TYPE"];
				$object[$data["Field"]]["size"] = $match["SIZE"];
			} else {
				$object[$data["Field"]]["type"] = $data["Type"];
			}

			if ($data["Default"] !== NULL) {
				$object[$data["Field"]]["default"] = $data["Default"];
			}
			if ($data["Extra"] == "auto_increment") {
				$object[$data["Field"]]["auto_increment"] = true;
			}
		}
		return $object;
	}
}
