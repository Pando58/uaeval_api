<?php

class status {
  public static function error($err_id, $msg = '') {
    return array(
      'status' => 'error',
      'err_id' => $err_id,
      'msg' => $msg
    );
  }

  public static function ok($data = []) {
    return array(
      'status' => 'ok',
      'data' => $data
    );
  }
}

?>