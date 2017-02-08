<?php
class MySQLDB {

	private $conn;

	function __construct($db_location, $db_user, $db_password, $db_name) {
		$this->conn = new mysqli($db_location, $db_user, $db_password, $db_name);
		if ($this->conn->connect_error) {
			die(mb_convert_encoding('Ошибка подключения (' . $this->conn->connect_errno . ') '
				. $this->conn->connect_error, "UTF-8"));
		}
	}

	public function select($fields,$table,$order) {

		$query = sprintf("SELECT %s FROM %s ORDER by %s DESC;", implode($fields,','), $table, $order);

		$result = $this->conn->query($query);
		$result_array = array();
		if ($result) {

			/* добавление выборки в массив для вывода */
			while ($row = $result->fetch_assoc()) {
				array_push($result_array, $row);
			}

			/* удаление выборки */
			$result->free();
		}

		return $result_array;
	}
}
?>