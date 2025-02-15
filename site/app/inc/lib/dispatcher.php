<?php

class Dispatcher
{
	private array $_class_args = [];
	private string $_request_server = "";
	private string $_request_uri = "";
	private string $_path_info = "";
	private array $_file_default_list = ["index", "index.php", "dispatcher.php", "webapp.php", "index.html"];
	private bool $_rewrite = true;
	private array $_routes = [];

	public function __construct(bool $rewrite = true, array $class_args = [])
	{
		$this->_rewrite = $rewrite;
		$this->_class_args = $class_args;
		$this->_request_server = $this->get_request_server();
		$this->_request_uri = $this->get_request_uri();
		$this->_path_info = $this->get_path_info();
		$this->normalize_request();
	}

	private function normalize_request(): void
	{
		$path_length = strlen($this->_path_info);
		if ($path_length > 0 && $this->_path_info[$path_length - 1] === "/") {
			$this->basic_redir($this->_request_server . rtrim($_SERVER["REQUEST_URI"], "/"));
		}
	}

	public function set_file_default_list(array $value): void
	{
		$this->_file_default_list = array_merge($this->_file_default_list, $value);
	}

	public function get_path_info(bool $levels = false): string|array
	{
		$path = $this->_path_info ?: getenv("PATH_INFO") ?: getenv("REQUEST_URI");
		$path = preg_replace("/^(.+)\?.+$/", "$1", $path);
		if ($path === "/") {
			$path = "index.php";
		}
		if (in_array(trim($path, "/"), $this->_file_default_list)) {
			$path = "";
		}
		return $levels ? explode("/", trim($path, "/")) : $path;
	}

	public function get_request_uri(): string
	{
		if (empty($_SERVER["SCRIPT_NAME"])) {
			return "";
		}
		$tmp_script_name = $_SERVER["SCRIPT_NAME"];
		$tmp_file_name = basename($_SERVER["SCRIPT_NAME"]);
		if (in_array($tmp_file_name, $this->_file_default_list) && $this->_rewrite) {
			$tmp_script_name = str_replace($tmp_file_name, "", $tmp_script_name);
		}
		return rtrim($tmp_script_name, "/");
	}

	public function get_request_full_uri(): string
	{
		return $this->_request_server . $this->_request_uri;
	}

	public function add_route(string $http_method, string $url_pattern, $exec, $check = null, $args = [], ?string $name = null): void
	{
		if (!is_array($args)) {
			$args = [$args];
		}
		if (in_array($http_method, ["POST", "GET"]) && !empty($exec)) {
			$this->_routes[$name ?? count($this->_routes)] = [
				"http_method" => $http_method,
				"url_pattern" => $url_pattern,
				"exec" => $exec,
				"check" => $check,
				"args" => $args
			];
		}
	}

	public function exec(): bool
	{
		$server_method = $_SERVER["REQUEST_METHOD"];
		foreach ($this->_routes as $entry) {
			if ($server_method === $entry["http_method"]) {
				if ($entry["check"] === false) {
					continue;
				}
				if (preg_match("/^" . str_replace("/", "\\/", $entry["url_pattern"]) . "$/", $this->_path_info, $matches)) {
					$class = $method_name = null;
					if (is_string($entry["exec"])) {
						if (str_starts_with($entry["exec"], "function:")) {
							$function_name = substr($entry["exec"], strlen("function:"));
							if (function_exists($function_name)) {
								$entry["args"] = array_merge($entry["args"], $matches);
								return call_user_func($function_name, $entry["args"]);
							}
						} else {
							[$class_name, $method_name] = explode(":", $entry["exec"]);
							if (class_exists($class_name)) {
								$class = empty($this->_class_args) ? new $class_name : new $class_name($this->_class_args);
							}
						}
					}
					if (is_array($entry["exec"]) && count($entry["exec"]) === 2) {
						[$class, $method_name] = $entry["exec"];
					}
					if (isset($class, $method_name) && is_string($method_name) && is_object($class) && method_exists($class, $method_name)) {
						$matches["server_uri"] = $this->_path_info;
						$matches = array_merge($entry["args"], $matches);
						$class->{$method_name}($matches);
						return true;
					}
				}
			}
		}
		return false;
	}

	private function get_request_server(): string
	{
		return htmlspecialchars($_SERVER['SERVER_NAME'] ?? '', ENT_QUOTES, 'UTF-8');
	}

	private function basic_redir(string $url): void
	{
		header("Location: $url");
		exit;
	}
}