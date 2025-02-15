<?php
class local_mysqli extends mysqli
{
	public function __construct($sys = NULL)
	{
		$host = getenv('DB_HOST') ?: 'localhost';
		$user = getenv('DB_USER') ?: 'root';
		$pass = getenv('DB_PASS') ?: '';
		$db = getenv('DB_NAME') ?: 'test';

		if (!parent::real_connect($host, $user, $pass, $db)) {
			throw new Exception('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		}
		parent::set_charset("utf8mb4");
	}

	public function select(string $fields, string $table, string $options = ''): mysqli_result|bool
	{
		$query = sprintf("SELECT %s FROM %s %s", $fields, $table, $options);
		return $this->my_query($query);
	}

	public function insert(string $fields, string $table, string $options = ''): bool
	{
		$query = sprintf("INSERT INTO %s SET %s %s", $table, $fields, $options);
		return $this->my_query($query);
	}

	public function replace(string $fields, string $table): bool
	{
		$query = sprintf("REPLACE INTO %s SET %s", $table, $fields);
		return $this->my_query($query);
	}

	public function update(string $fields, string $table, string $options = ''): bool
	{
		$query = sprintf("UPDATE %s SET %s %s", $table, $fields, $options);
		return $this->my_query($query);
	}

	public function delete(string $table, string $options = ''): bool
	{
		$query = sprintf("DELETE FROM %s %s", $table, $options);
		return $this->my_query($query);
	}

	private function my_query(string $query): mysqli_result|bool
	{
		$result = $this->query($query);
		if (!$result) {
			throw new Exception("SQL error: $query \n " . $this->error);
		}
		return $result;
	}

	public function recordcount(mysqli_result $res): int
	{
		return $res->num_rows;
	}

	public function result(mysqli_result $res, string $name, int $position): mixed
	{
		if ($position >= $res->num_rows) return false;
		$res->data_seek($position);
		$line = $res->fetch_array(MYSQLI_ASSOC);
		return $line[$name] ?? false;
	}

	public function results(mysqli_result $res): array
	{
		$obj = [];
		while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
			$obj[] = $row;
		}
		return $obj;
	}

	public function fields_config(string $table): array
	{
		$object = [];
		$res = $this->my_query(sprintf("SHOW COLUMNS FROM %s", $table));

		foreach ($this->results($res) as $data) {
			$field = $data["Field"];
			$object[$field] = [
				"PK" => $data["Key"] === "PRI",
				"UNI" => $data["Key"] === "UNI",
				"type" => $data["Type"],
				"default" => $data["Default"] ?? null,
				"auto_increment" => $data["Extra"] === "auto_increment"
			];

			if (preg_match("/(?P<TYPE>\w+)\((?P<SIZE>.+)\)/", $data["Type"], $match)) {
				$object[$field]["type"] = $match["TYPE"];
				$object[$field]["size"] = $match["SIZE"];
			}
		}
		return $object;
	}
}