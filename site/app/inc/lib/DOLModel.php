<?php

declare(strict_types=1);

class DOLModel extends rootOBJ
{
	private $con;
	private $table;
	private $schema;
	private $keys;
	private $field;
	private $filter;
	private $paginate;
	private $order;
	private $group;
	public $data;
	private $recordset;

	public function __construct(string $table)
	{
		$this->con = new local_mysqli();
		$this->set_table($table);
		$this->set_schema($this->con->fields_config($this->table));
		$keys = ['pk' => [], 'UNI' => []];
		foreach ($this->schema as $key => $value) {
			if (isset($value["PK"])) {
				$keys["pk"][] = $key;
			}
			if (isset($value["UNI"])) {
				$keys["UNI"][] = $key;
			}
		}
		$this->set_keys($keys);
	}

	public function save(): bool
	{
		if (isset($this->field)) {
			if (isset($this->field["idx"])) {
				unset($this->field["idx"]);
			}
			$ff = implode(" , ", $this->field);
			if (preg_match("/'/", $ff)) {
				if (isset($this->filter) && is_array($this->filter) && count($this->filter) === 1 && trim($this->filter[0]) === "active = 'yes'") {
					unset($this->filter);
				}
				if (isset($this->filter) && is_array($this->filter)) {
					$fi = " where " . implode(" and ", $this->filter) . " ";
					$pa = $this->paginate ? " limit " . implode(" , ", $this->paginate) . " " : "";
					$ff .= ", modified_at = now(), modified_by = '" . $this->con->real_escape_string((string)($_SESSION[constant("cAppKey")]["credential"]["idx"] ?? 0)) . "'";
					return $this->con->update($ff, $this->table, $fi . $pa);
				} else {
					$ff .= ", created_at = now(), created_by = '" . $this->con->real_escape_string((string)($_SESSION[constant("cAppKey")]["credential"]["idx"] ?? 0)) . "'";
					return $this->con->insert($ff, $this->table, null);
				}
			}
		}
		return false;
	}

	public function remove(): bool
	{
		if (isset($this->filter)) {
			$fi = " where " . implode(" and ", $this->filter) . " ";
			$pa = $this->paginate ? " limit " . implode(" , ", $this->paginate) . " " : "";
			$ff = " active = 'no', removed_at = now(), removed_by = '" . $this->con->real_escape_string($_SESSION[constant("cAppKey")]["credential"]["idx"] ?? 0) . "'";
			return $this->con->update($ff, $this->table, $fi . $pa);
		}
		return false;
	}

	public function populate(array $data, bool $encode = false): void
	{
		$array = [];
		foreach ($this->schema as $key => $value) {
			if (isset($data[$key])) {
				if ($encode) {
					$data[$key] = utf8_decode($data[$key]);
				}
				if (strtolower($data[$key])) {
					$array[$key] = sprintf(
						" %s = '%s' ",
						$key,
						$this->con->real_escape_string($data[$key])
					);
				}
			}
		}
		if (count($array)) {
			$this->set_field($array);
		}
	}

	public function return_data(): array
	{
		$this->load_data();
		return [$this->recordset, $this->data];
	}

	public function _list_data(string $value = "name", array $filter = [], string $key = "idx", string $order = ""): array
	{
		$this->set_field([$key, $value]);
		$this->set_filter(count($filter) ? array_merge(["active = 'yes'"], $filter) : ["active = 'yes'"]);
		$this->set_order([$order === "" ? preg_replace("/.+ as (.+)$/", "$1", $value) . " asc " : $order]);
		$this->load_data();
		return $this->data;
	}

	public function _current_data(array $filter = [], array $fields = [], array $attach = [], array $attach_son = [], $availabled = false): array
	{
		$field = ["idx", "DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as created_at", "DATE_FORMAT(modified_at, '%d/%m/%Y %H:%i') as modified_at"];
		if (!count($filter)) {
			$filter = ["idx = -1"];
		}
		if (count($fields)) {
			$field = array_merge($field, $fields);
		}
		$this->set_field($field);
		$this->set_filter($filter);
		$this->set_paginate([1]);
		$this->load_data();

		if (count($attach)) {
			foreach ($attach as $v) {
				$this->attach([$v["name"]], $v["direction"] ?? false, $v["specific"] ?? "");
			}
		}
		if (count($attach_son)) {
			foreach ($attach_son as $v) {
				$this->attach_son($v[0], [$v[1]["name"]], $v[1]["direction"] ?? "", $v[1]["options"] ?? "");
			}
		}
		if ($availabled !== false && count($availabled)) {
			$this->data[0]["_availabe_attach"] = $availabled;
			foreach ($availabled as $key => $value) {
				if (isset($this->data[0][$key . "_attach"][0])) {
					foreach ($this->data[0][$key . "_attach"] as $v) {
						$this->data[0]["_availabe_attach"][$key]["data"][] = $v["idx"];
					}
				}
			}
		}
		return current($this->data);
	}

	public function load_data(): void
	{
		$ff = isset($this->field) ? implode(",", $this->field) : " * ";
		$fi = isset($this->filter) ? " where " . implode(" and ", $this->filter) . " " : "";
		$or = isset($this->order) ? " order by " . implode(" , ", $this->order) . " " : "";
		$gp = isset($this->group) ? " group by " . implode(" , ", $this->group) . " " : "";
		$pa = isset($this->paginate) ? " limit " . implode(" , ", $this->paginate) . " " : "";
		$r = $this->con->select($ff, $this->table, $fi . $gp . $or . $pa);
		$this->set_data($this->con->results($r));
		$this->set_recordset($this->con->result($this->con->select("count(" . implode(",", $this->keys["pk"]) . ") as q", $this->table, $fi . $gp), "q", 0));
	}

	public function attach(array $classes = [], ?string $reverse_table = null, ?string $options = null, ?array $class_field = null): void
	{
		$new_data = [];
		$_data = $this->data;
		foreach ($_data as $key => $value) {
			$new_data[$key] = $value;
			foreach ($classes as $class) {
				$r = $this->con->select(
					sprintf("%s_id as k", $class),
					sprintf("%s_%s", $reverse_table ? $class : $this->table, $reverse_table ? $this->table : $class),
					sprintf(" where active = 'yes' and %s_id = '%d'", $this->table, $value["idx"])
				);
				$filter_key = ['0'];
				foreach ($this->con->results($r) as $data) {
					$filter_key[] = "'" . $data["k"] . "'";
				}
				$r = $this->con->select(
					$class_field ? implode(", ", $class_field) : "*",
					$class,
					sprintf(" where active = 'yes' and idx in (%s) %s", implode(",", array_unique($filter_key)), $options)
				);
				$new_data[$key][$class . "_attach"] = $this->con->results($r);
			}
		}
		$this->set_data($new_data);
	}

	public function join(?string $name = null, ?string $table = null, array $fw_key = [], ?string $options = null, ?array $field = null): void
	{
		$new_data = [];
		$_data = $this->get_data();
		foreach ($_data as $key => $value) {
			$new_data[$key] = $value;
			$flt = ["active = 'yes'"];
			foreach ($fw_key as $fw_keys => $data_value) {
				if (isset($value[$data_value])) {
					$flt[] = $fw_keys . " = '" . $value[$data_value] . "' ";
				}
			}
			if (count($flt) > 1 || !empty($options)) {
				$r = $this->con->select($field ? implode(", ", $field) : "*", $table, " where " . implode(" and ", $flt) . strtr($options, ["#IDX#" => $value["idx"]]));
				$new_data[$key][$name . "_attach"] = $this->con->results($r);
			} else {
				$new_data[$key][$name . "_attach"] = [];
			}
		}
		$this->set_data($new_data);
	}

	public function attach_son(string $classesfather = "", array $classes = [], ?string $reverse_table = null, ?string $options = null, ?array $class_field = null): void
	{
		if ($classesfather !== "" && count($classes)) {
			$new_data = [];
			$_data = $this->data;
			foreach ($_data as $key => $value) {
				$new_data[$key] = $value;
				if (isset($new_data[$key][$classesfather . "_attach"]) && count($new_data[$key][$classesfather . "_attach"])) {
					foreach ($new_data[$key][$classesfather . "_attach"] as $v) {
						foreach ($classes as $class) {
							$r = $this->con->select(
								sprintf("%s_id as k", $class),
								sprintf("%s_%s", $reverse_table ? $class : $classesfather, $reverse_table ? $classesfather : $class),
								sprintf(" where active = 'yes' and %s_id = '%d'", $classesfather, $this->con->real_escape_string($v["idx"]))
							);
							$filter_key = ['0'];
							foreach ($this->con->results($r) as $data) {
								$filter_key[] = $data["k"];
							}
							$r = $this->con->select(
								$class_field[$class] ? implode(", ", $class_field[$class]) : "*",
								$class,
								sprintf(" where active = 'yes' and idx in ('%s') %s", implode("','", array_unique($filter_key)), $options ? preg_replace("/%s/im", $this->con->real_escape_string($value["idx"]), $options) : "")
							);
							$new_data[$key][$classesfather . "_attach"][$v["idx"]][$class . "_attach"] = $this->con->results($r);
						}
					}
				}
			}
			$this->set_data($new_data);
		}
	}

	public function save_attach(array $info, array $classes = [], ?string $reverse_table = null): void
	{
		foreach ($classes as $class) {
			if (isset($info["post"][$class . "_id"])) {
				$execute = $info["post"][$class . "_id"];
				$varexecute = is_array($execute) ? $execute : [(int)$execute];

				if (count($varexecute)) {
					$this->con->update(
						sprintf("active = 'no', removed_at = now(), removed_by = '%d'", $this->con->real_escape_string($_SESSION[constant("cAppKey")]["credential"]["idx"] ?? 0)),
						sprintf("%s_%s", $reverse_table ? $class : $this->table, $reverse_table ? $this->table : $class),
						sprintf("where active='yes' and %s_id = '%d'", $this->table, $this->con->real_escape_string($info["idx"]))
					);
					foreach ($varexecute as $var) {
						$sql = sprintf(
							"INSERT INTO %s (%s, %s, created_by, created_at) VALUES('%d', '%d', %d, now()) ON DUPLICATE KEY UPDATE active = 'yes', removed_at = NULL, removed_by = NULL, modified_at=now(), modified_by='%d'",
							sprintf("%s_%s", $reverse_table ? $class : $this->table, $reverse_table ? $this->table : $class),
							sprintf("%s_id", $class),
							sprintf("%s_id", $this->table),
							$this->con->real_escape_string($var),
							$this->con->real_escape_string($info["idx"]),
							$this->con->real_escape_string($_SESSION[constant("cAppKey")]["credential"]["idx"] ?? 0),
							$this->con->real_escape_string($_SESSION[constant("cAppKey")]["credential"]["idx"] ?? 0)
						);
						$this->con->query($sql);
					}
				}
			}
		}
	}

	// Add the missing setter methods
	private function set_con($con): void
	{
		$this->con = $con;
	}

	private function set_table($table): void
	{
		$this->table = $table;
	}

	private function set_schema($schema): void
	{
		$this->schema = $schema;
	}

	private function set_keys($keys): void
	{
		$this->keys = $keys;
	}

	private function set_field($field): void
	{
		$this->field = $field;
	}

	private function set_filter($filter): void
	{
		$this->filter = $filter;
	}

	private function set_paginate($paginate): void
	{
		$this->paginate = $paginate;
	}

	private function set_order($order): void
	{
		$this->order = $order;
	}

	private function set_group($group): void
	{
		$this->group = $group;
	}

	private function set_data($data): void
	{
		$this->data = $data;
	}

	private function set_recordset($recordset): void
	{
		$this->recordset = $recordset;
	}

	private function get_data()
	{
		return $this->data;
	}
}
