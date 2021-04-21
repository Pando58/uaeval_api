<?php

class ConexionDB {
  private $server;
  private $user;
  private $pass;
  private $db;
  public $dbh;

  function __construct() {
    $this->server = 'localhost';
    $this->user = 'root';
    $this->pass = '';
    $this->db = 'uaeval';

    $this->dbh = new PDO('mysql:host='.$this->server.';dbname='.$this->db, $this->user, $this->pass);
    $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
}

?>