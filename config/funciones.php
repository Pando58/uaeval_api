<?php

class funciones {
  public static function revisarEstadoLogin($dbh, $userdata) {
    $cookies = $userdata['cookies'];
    $session = $userdata['session'];
    
    if (!isset($session['id']) || !isset($cookies['PHPSESSID'])) {
      session_start();
    }

    if (isset($cookies['id']) && isset($cookies['token']) && isset($cookies['serial'])) {
      $userid = $cookies['userid'];
      $token = $cookies['token'];
      $serial = $cookies['serial'];

      $query = 'SELECT * FROM sesiones WHERE user_id = :uid AND token = :token AND serial = :serial';
      
      $stmt = $dbh->prepare($query);
      $stmt->bindParam(':uid', $userid);
      $stmt->bindParam(':token', $token);
      $stmt->bindParam(':serial', $serial);
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($row['user_id'] > 0) {
        if (
          $row['user_id'] == $cookies['userid'] &&
          $row['token'] == $cookies['token'] &&
          $row['serial'] == $cookies['serial']
        ) {
          if (
            $row['user_id'] == $session['userid'] &&
            $row['token'] == $session['token'] &&
            $row['serial'] == $session['serial']
          ) {
            return true;
          }
        }
      }
    }
  }
}

?>