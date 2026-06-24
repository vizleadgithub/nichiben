<?php
date_default_timezone_set('Asia/Tokyo');
class DbConnect {
	const DB_SERVER = 'nichibenren-stag-database.cluster-cjxkbcyhrbuv.ap-northeast-1.rds.amazonaws.com';
	const DB_TYPE = 'mysql';
	const DB_NAME = 'alflearning';
	const DB_USER = 'alflearning';
	const DB_PASS = 'grefvsdj43t5yry';

	public $connect;

	// コンストラクタ
	public function __construct() {
		mb_language('Japanese');
		date_default_timezone_set('Asia/Tokyo');

		$this->connect = mysqli_connect(self::DB_SERVER, self::DB_USER, self::DB_PASS, self::DB_NAME);
		
		if (!$this->connect) {
			die("Database Connection Failed: " . mysqli_connect_error());
		}
		
		mysqli_set_charset($this->connect, "utf8");
	}

	// 切断
	public function close() {
		if ($this->connect) {
			mysqli_close($this->connect);
		}
	}

	// クエリ送信
	public function query($sql) {
		$result = mysqli_query($this->connect, $sql);
		if (!$result) {
			//die("SQL Error: " . mysqli_error($this->connect));
			$result = [];
		}
		return $result;
	}

	// フェッチ
	public function fetch($result) {
		return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
	}

	public function fetch_arr($result) {
		return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
	}

	public function query_fetch_arr($sql) {
		$result = $this->query($sql);
		return $this->fetch_arr($result);
	}

	// クエリ送信とフェッチ
	public function query_fetch($sql) {
		$result = $this->query($sql);
		$rows = $this->fetch_arr($result);
		return $rows ? $rows[0] : [];
	}

	// 実行
	public function execute($sql) {
		return $this->query($sql);
	}

	// トランザクション開始
	public function tran_begin() {
		$this->query('START TRANSACTION');
	}

	// ロールバック
	public function rollback() {
		$this->query('ROLLBACK');
	}

	// コミット
	public function commit() {
		$result = $this->query('COMMIT');
		if (!$result) {
			$this->rollback();
		}
		return $result;
	}

	public function get_thread_id($table_name) {
		$sql = 'SELECT LAST_INSERT_ID() FROM ' . $table_name;
		$result = $this->query($sql);
		$row = $result->fetch_array();
		return $row ? $row[0] : null;
	}
} 

?>