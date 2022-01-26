<?php


class Database {
  private $connection;
  private static $db;

  public static function getInstance($option = null) {
    if (self::$db == null) {
      self::$db = new Database($option);
    }
    return self::$db;
  }

  private function __construct($option = null) {
    if ($option != null) {
      $host = $option['host'];
      $user = $option['user'];
      $pass = $option['pass'];
      $name = $option['name'];
    } else {
      global $config;
      $host = $config['host'];
      $user = $config['user'];
      $pass = $config['pass'];
      $name = $config['name'];
    }
    $this->connection = new mysqli($host, $user, $pass, $name);
    if ($this->connection->connect_error) {
      echo "Connection failed: " . $this->connection->connect_error;
      exit;
    }
    $this->connection->query("SET NAMES 'ut8'");
  }

  public function query($sql) {
    return $this->connection->query($sql);
  }

  public function insertUserData($chatId, $date) {
    $this->query("INSERT INTO `_user_data` VALUES (NULL, '" . $date . "', '" . $chatId . "')");
  }

  public function searchBlog($blog) {
    $sql = "SELECT * FROM `_blogData` WHERE `_url` LIKE '" . $blog . "' ";
    $result = $this->query($sql);
    if (mysqli_num_rows($result) > 0)
      return true;
    else
      return false;
  }

  public function insertBlog($blog, $date, $yearMonth, $day) {
    $this->query("INSERT INTO `_blogData` VALUES (NULL, '" . $blog . "', '" . $date . "','" . $yearMonth . "','" . $day . "', '1', '1')");
  }

  public function getBlogData($blog) {
    $data = array();
    $sql = "SELECT * FROM `_blogData` WHERE `_url` LIKE '" . $blog . "'";
    $result = $this->query($sql);
    $fetch = mysqli_fetch_array($result);
    $data['_id'] = $fetch['_id'];
    $data['_dDay'] = $fetch['_day'];
    $data['_day'] = $fetch['_visitDay'];
    $data['_month'] = $fetch['_visitMonth'];
    $data['_dYearMonth'] = $fetch['_yearMonth'];
    return $data;
  }

  public function updateBlog($format, $id, $dYearMonth, $dDay, $day, $month) {
    switch ($format) {
      case "YearMonth":
        $this->query("UPDATE `_blogData` SET `_yearMonth` = '" . $dYearMonth . "', `_day` = '" . $dDay . "' WHERE `_id` = " . $id);
        break;
      case "MonthDay":
        $this->query("UPDATE `_blogData` SET `_visitDay` = '" . $day . "', `_visitMonth` = '" . $month . "' WHERE `_id` = " . $id);
        break;
      case "Month":
        $this->query("UPDATE `_blogData` SET `_day` = '" . $dDay . "', `_visitDay` = '1', `_visitMonth` = '" . $month . "' WHERE `_id` = " . $id);
        break;
    }
  }

  public function getTableCount($table) {
    $result = $this->query("SELECT COUNT(*) AS _count FROM `" . $table . "` ");
    return $result->fetch_array()['_count'];
  }
}
