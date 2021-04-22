<?php

require_once '../config/header.php';
require_once '../clases/status.php';

// Evitar error de CORS
if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
  header("HTTP/1.1 200 OK");
  exit;
}

// No existe el token en el header
if (!getAuthorizationHeader()) {
  header('HTTP/1.0 400 Bad Request');
} else {
  $jwt = getBearerToken();

  if (!$jwt) {
    header('HTTP/1.0 400 Bad Request');
  } else {
    // Validar token
  }
}



function getAuthorizationHeader() {
  $headers = null;
  
  if (isset($_SERVER['Authorization'])) {
      $headers = trim($_SERVER["Authorization"]);
  } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
  } else if (function_exists('apache_request_headers')) {
    $requestHeaders = apache_request_headers();
    $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
    
    if (isset($requestHeaders['Authorization'])) {
      $headers = trim($requestHeaders['Authorization']);
    }
  }

  return $headers;
}

function getBearerToken() {
  $headers = getAuthorizationHeader();
  if (!empty($headers)) {
    if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
        return $matches[1];
    }
  }

  return null;
}

?>