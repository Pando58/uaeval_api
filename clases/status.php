<?php

class status {
  public static function error($err_id, $msg = '') {
    return [
      'status' => 'error',
      'err_id' => $err_id,
      'msg' => $msg
    ];
  }

  public static function ok($data = []) {
    return [
      'status' => 'ok',
      'data' => $data
    ];
  }
}

?>