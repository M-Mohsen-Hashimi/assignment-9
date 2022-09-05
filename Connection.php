<?php
if (!isset($_SESSION)) {
  session_start();
}

class Connection
{
  private $server = "mysql:host=localhost;dbname=employee_mis";
  private $username = "root";
  private $password = "";
  private $options  = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  );
  protected $conn;

  public function open()
  {
    try {
      $this->conn = new PDO(
        $this->server,
        $this->username,
        $this->password,
        $this->options,

      );
      return $this->conn;
    } catch (PDOException $e) {
      echo "There is some problem in connection: " . $e->getMessage();
    }
  }

  public function close()
  {
    $this->conn = null;
  }
}
