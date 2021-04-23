<?php

use Firebase\JWT\JWT;

class AuthToken {
  private static $key = 'unialvae';
  private static $encrypt = ['HS256'];
  private static $aud = null;
  private static $exp = (60 * 60);

  public static function generarToken($datos) {
    $t = time();
    
    $token = array(
      'iat' => $t,
      'exp' => $t + self::$exp,
      'aud' => self::Aud(),
      'data' => $datos
    );

    return JWT::encode($token, self::$key);
  }

  public static function validarToken($token) {
    if (empty($token)) {
      throw new Exception('El token no es valido');
    }

    $decode = JWT::decode($token, self::$key, self::$encrypt);

    if ($decode->aud !== self::Aud()) {
      throw new Exception('El usuario no es valido');
    }
  }

  public static function obtenerDatosToken($token) {
    return JWT::decode($token, self::$key, self::$encrypt);
  }

  private static function Aud() {
    $aud = '';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $aud = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $aud = $_SERVER['REMOTE_ADDR'];
    }

    $aud .= @$_SERVER['HTTP_USER_AGENT'];
    $aud .= gethostname();

    return sha1($aud);
  }
}

?>