<?php

class ConexionDB {
  private $server;
  private $user;
  private $pass;
  private $db;
  public $dbh;

  function __construct() {
    $conf = json_decode(file_get_contents('../config/server_config.json'));
    
    $this->server = $conf->server ?? 'localhost';
    $this->user = $conf->user ?? 'root';
    $this->pass = $conf->pass ?? '';
    $this->db = $conf->db ?? 'uaeval';

    $this->dbh = new PDO('mysql:host='.$this->server.';dbname='.$this->db, $this->user, $this->pass);
    $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
}

?>